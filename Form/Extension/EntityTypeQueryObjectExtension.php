<?php

namespace Imatic\Bundle\DataBundle\Form\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityLoaderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractTypeExtension;

/**
 * Form type theme extension
 *
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
class EntityTypeQueryObjectExtension extends AbstractTypeExtension
{
    /** @var QueryExecutorInterface */
    private $queryExecutor;

    public function __construct(QueryExecutorInterface $queryExecutor)
    {
        $this->queryExecutor = $queryExecutor;
    }

    /**
     * @param EntityManagerInterface $em
     * @param QueryObjectInterface   $queryObject
     * @return EntityLoaderInterface
     */
    public function getLoader(EntityManagerInterface $em, QueryObjectInterface $queryObject)
    {
        return new EntityTypeQueryObjectLoader(
            $em,
            $this->queryExecutor,
            $queryObject
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_object' => null,
            'loader' => function (Options $options, $previousLoader) {
                if (null !== $options['query_object']) {
                    if (null !== $options['query_builder']) {
                        throw new \LogicException('Cannot use both "query_object" and "query_builder" options');
                    }

                    return $this->getLoader(
                        $options['em'],
                        $options['query_object']
                    );
                } else {
                    return $previousLoader;
                }
            },
        ]);

        $resolver->setAllowedTypes(
            'query_object', ['null', 'Imatic\Bundle\DataBundle\Data\Query\QueryObjectInterface']
        );
    }

    public function getExtendedType()
    {
        return 'entity';
    }
}
