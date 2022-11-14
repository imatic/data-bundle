<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use ArrayIterator;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Internal\SQLResultCasing;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\LimitSubqueryWalker;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\WhereInWalker;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator\CountOutputWalker;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Paginator\LimitSubqueryOutputWalker;

/**
 * Copy / paste of original paginator with different output walkers.
 *
 * It extends the original paginator so that this one can be passed in all places original can.
 */
class Paginator extends DoctrinePaginator
{
    use SQLResultCasing;

    private Query $query;
    private bool $fetchJoinCollection;
    private ?bool $useOutputWalkers = null;
    private ?int $count = null;

    /**
     * Constructor.
     *
     * @param Query|QueryBuilder $query               A Doctrine ORM query or query builder.
     * @param bool               $fetchJoinCollection Whether the query joins a collection (true by default).
     */
    public function __construct($query, bool $fetchJoinCollection = true)
    {
        parent::__construct($query, $fetchJoinCollection);

        if ($query instanceof QueryBuilder) {
            $query = $query->getQuery();
        }

        $this->query = $query;
        $this->fetchJoinCollection = $fetchJoinCollection;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Returns whether the query joins a collection.
     *
     * @return bool Whether the query joins a collection.
     */
    public function getFetchJoinCollection(): bool
    {
        return $this->fetchJoinCollection;
    }

    /**
     * Returns whether the paginator will use an output walker.
     */
    public function getUseOutputWalkers(): ?bool
    {
        return $this->useOutputWalkers;
    }

    /**
     * Sets whether the paginator will use an output walker.
     *
     * @param bool|null $useOutputWalkers
     */
    public function setUseOutputWalkers($useOutputWalkers): self
    {
        $this->useOutputWalkers = $useOutputWalkers;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->count === null) {
            try {
                $this->count = \array_sum(\array_map('current', $this->getCountQuery()->getScalarResult()));
            } catch (NoResultException $e) {
                $this->count = 0;
            }
        }

        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $offset = $this->query->getFirstResult();
        $length = $this->query->getMaxResults();

        if ($this->fetchJoinCollection) {
            $subQuery = $this->cloneQuery($this->query);

            if ($this->useOutputWalker($subQuery)) {
                $subQuery->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, LimitSubqueryOutputWalker::class);
            } else {
                $this->appendTreeWalker($subQuery, LimitSubqueryWalker::class);
                $this->unbindUnusedQueryParams($subQuery);
            }

            $subQuery->setFirstResult($offset)->setMaxResults($length);

            $ids = \array_map('current', $subQuery->getScalarResult());

            $whereInQuery = $this->cloneQuery($this->query);
            // don't do this for an empty id array
            if (\count($ids) === 0) {
                return new ArrayIterator([]);
            }

            $this->appendTreeWalker($whereInQuery, WhereInWalker::class);
            $whereInQuery->setHint(WhereInWalker::HINT_PAGINATOR_ID_COUNT, \count($ids));
            $whereInQuery->setFirstResult(null)->setMaxResults(null);
            $whereInQuery->setParameter(WhereInWalker::PAGINATOR_ID_ALIAS, $ids);
            $whereInQuery->setCacheable($this->query->isCacheable());

            $result = $whereInQuery->getResult($this->query->getHydrationMode());
        } else {
            $result = $this->cloneQuery($this->query)
                ->setMaxResults($length)
                ->setFirstResult($offset)
                ->setCacheable($this->query->isCacheable())
                ->getResult($this->query->getHydrationMode());
        }

        return new ArrayIterator($result);
    }

    /**
     * Clones a query.
     *
     * @param Query $query The query.
     *
     * @return Query The cloned query.
     */
    private function cloneQuery(Query $query)
    {
        /* @var $cloneQuery Query */
        $cloneQuery = clone $query;

        $cloneQuery->setParameters(clone $query->getParameters());
        $cloneQuery->setCacheable(false);

        foreach ($query->getHints() as $name => $value) {
            $cloneQuery->setHint($name, $value);
        }

        return $cloneQuery;
    }

    /**
     * Determines whether to use an output walker for the query.
     *
     * @param Query $query The query.
     *
     * @return bool
     */
    private function useOutputWalker(Query $query)
    {
        if ($this->useOutputWalkers === null) {
            return (bool) $query->getHint(Query::HINT_CUSTOM_OUTPUT_WALKER) === false;
        }

        return $this->useOutputWalkers;
    }

    /**
     * Appends a custom tree walker to the tree walkers hint.
     */
    private function appendTreeWalker(Query $query, string $walkerClass): void
    {
        $hints = $query->getHint(Query::HINT_CUSTOM_TREE_WALKERS);

        if ($hints === false) {
            $hints = [];
        }

        $hints[] = $walkerClass;
        $query->setHint(Query::HINT_CUSTOM_TREE_WALKERS, $hints);
    }

    /**
     * Returns Query prepared to count.
     */
    private function getCountQuery(): Query
    {
        $countQuery = $this->cloneQuery($this->query);

        if (!$countQuery->hasHint(CountWalker::HINT_DISTINCT)) {
            $countQuery->setHint(CountWalker::HINT_DISTINCT, true);
        }

        if ($this->useOutputWalker($countQuery)) {
            $platform = $countQuery->getEntityManager()->getConnection()->getDatabasePlatform(); // law of demeter win

            $rsm = new ResultSetMapping();

            $rsm->addScalarResult($this->getSQLResultCasing($platform, 'dctrn_count'), 'count');
            $countQuery->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, CountOutputWalker::class);
            $countQuery->setResultSetMapping($rsm);
        } else {
            $this->appendTreeWalker($countQuery, CountWalker::class);
            $this->unbindUnusedQueryParams($countQuery);
        }

        $countQuery->setFirstResult(null)->setMaxResults(null);

        return $countQuery;
    }

    private function unbindUnusedQueryParams(Query $query): void
    {
        $parser = new Parser($query);
        $parameterMappings = $parser->parse()->getParameterMappings();
        /* @var $parameters Collection|Parameter[] */
        $parameters = $query->getParameters();

        foreach ($parameters as $key => $parameter) {
            $parameterName = $parameter->getName();

            if (!(isset($parameterMappings[$parameterName]) || \array_key_exists($parameterName, $parameterMappings))) {
                unset($parameters[$key]);
            }
        }

        $query->setParameters($parameters);
    }
}
