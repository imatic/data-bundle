ImaticDataBundle
================

Vytvoření query objektu
-----------------------

* Query objekt slouží pro vytvoření query builderu

* musí implementovat QueryObjectInterface
* dále může implementovat jedno z rozhraní, které ovlivní co vrátí QueryExecutor:
    * SingleScalarResultQueryObjectInterface (skalární hodnotu)
    * ScalarResultQueryObjectInterface (pole skalárních hodnot)
    * SingleResultQueryObjectInterface (objekt)

.. sourcecode:: php

    <?php
    namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\QueryBuilder;
    use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
    use Imatic\Bundle\DataBundle\Data\Query\SingleResultQueryObjectInterface;

    class UserQuery implements QueryObjectInterface, SingleResultQueryObjectInterface
    {
        /**
         * @var int
         */
        private $id;

        // parametry QueryObjektu
        public function __construct($id)
        {
            $this->id = $id;
        }

        // implementovaná metoda vrací vytvořený QueryBuilder
        public function build(EntityManager $em)
        {
            return (new QueryBuilder($em))
                ->from('AppImaticDataBundle:User', 'u')
                ->select('u')
                ->where('u = :id')
                ->setParameter(':id', $this->id);
        }
    }

Vytvoření command handleru pro deaktivaci uživatele
---------------------------------------------------

* implementuje rozhraní: HandlerInterface
* slouží k provádění commandů (objekt s různými parametry, který se předá handleru)

.. sourcecode:: php

    <?php
    namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler;

    use Imatic\Bundle\DataBundle\Data\Command\CommandInterface;
    use Imatic\Bundle\DataBundle\Data\Command\HandlerInterface;
    use Imatic\Bundle\DataBundle\Data\ObjectManagerInterface;
    use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
    use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserQuery;

    class UserDeactivateHandler implements HandlerInterface
    {
        /**
         * @var QueryExecutorInterface
         */
        private $queryExecutor;

        /**
         * @var ObjectManagerInterface
         */
        private $objectManager;

        /**
         * @param QueryExecutorInterface $queryExecutor
         * @param ObjectManagerInterface $objectManager
         */
        public function __construct(QueryExecutorInterface $queryExecutor, ObjectManagerInterface $objectManager)
        {
            $this->queryExecutor = $queryExecutor;
            $this->objectManager = $objectManager;
        }

        // implementovaná metoda získá z commandu id uživatele a předá ho query objektu,
        // který vytvoří QueryBuilder a ten se pak předá QueryExecutoru, který vrátí právě 1 uživatele
        // (díky tomu, že QueryObjekt implementuje rozhraní SingleResultQueryObjectInterface)
        // který se následně deaktivuje a všechno se nakonec flushne aby se změny promítly do db.
        public function handle(CommandInterface $command)
        {
            $user = $this->queryExecutor->execute(new UserQuery($command->getParameter('id')));
            $user->deactivate();

            $this->objectManager->flush();
        }
    }

Registrace command handleru
---------------------------

.. sourcecode:: yaml

    services:
        app_imatic_data.handler.user_deactivate_handler:
            class: Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler\UserDeactivateHandler
            arguments:
                - @imatic_data.query_executor
                - @imatic_data.object_manager
            tags:
                - { name: imatic_data.handler, alias: user.deactivate }

Provedení commandu
------------------

.. sourcecode:: php

    <?php
    $id = 3;

    // vytvoří se Command, kterému se jako první argument předá alias handler
    // a jako druhý argument se pak předá pole parametrů
    $command = new Command('user.deactivate', ['id' => $id]);

    // nakonec se získá CommandQueryExecutor který může vrátit CommandResultInterface
    $result = $this->get('imatic_data.command_executor')->execute($command);

CommandResultInterface
----------------------

* je vracen jako výsledek CommandExecutoru

.. sourcecode:: php

    <?php
    namespace Imatic\Bundle\DataBundle\Data\Command;

    interface CommandResultInterface
    {
        /**
         * Vrátí pole zpráv
         *
         * @return MessageInterface[]
         */
        public function getMessages();

        /**
         * @return boolean
         */
        public function hasMessages();

        /**
         * @return boolean
         */
        public function isSuccessful();

        /**
         * @return boolean
         */
        public function hasException();

        /**
         * @return \Exception
         */
        public function getException();

        /**
         * @param MessageInterface $message
         */
        public function addMessage(MessageInterface $message);

        /**
         * @param MessageInterface[] $messages
         */
        public function addMessages(array $messages);
    }

Vytvoření filtru
----------------

* je potřeba podědit od třídy Filter a přepsat metodu configure, kde se pro každý filtrovatelný atribut entity musí nastavit filtr rule (pravidlo filtru+)

.. sourcecode:: php

    <?php
    namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User;

    use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter as FilterRule;
    use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;

    class UserFilter extends Filter
    {
        protected function configure()
        {
            $this
                // číselné filtrovaní podle id entity
                ->add(new FilterRule\NumberRule('id'))
                // textové filtrování podle name entity
                ->add(new FilterRule\TextRule('name'))
                // booleanovské filtrování podle activated entity
                ->add(new FilterRule\BooleanRule('activated'))
                // filtrování data podle intervalu
                ->add(new FilterRule\DateRangeRule('birthDate'))
                // filtrování vlasů podle jejich délky
                ->add(new FilterRule\ChoiceRule('hairs', ['long', 'short']))
            ;
        }
    }

Vytvoření query objektu s možností filtrování a sortování
---------------------------------------------------------

* pokud má být query objekt sortovatelný, musí implementovat rozhraní: SortableQueryObjectInterface
    * dále je nutné implementovat metodu: getSorterMap, která vrací pole kde klíč je název sorteru a hodnota je cesta k hodnotě v QueryBuilderu (metoda build)
* pokud má být query objekt filtrovatelný, musí implementovat rozhraní: FilterableQueryObjectInterface
    * dále je nutné implementovat metodu: getFilterMap, která vrací pole kde klíč je název filtru a hodnota je cesta k hodnotě v QueryBuilderu (metoda build)

.. sourcecode:: php

    <?php
    namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query;

    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\QueryBuilder;
    use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
    use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterableQueryObjectInterface;
    use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SortableQueryObjectInterface;

    class UserListQuery implements QueryObjectInterface, FilterableQueryObjectInterface, SortableQueryObjectInterface
    {
        /**
         * {@inheritdoc}
         */
        public function build(EntityManager $em)
        {
            return (new QueryBuilder($em))
                ->from('AppImaticDataBundle:User', 'u')
                ->select('u');
        }

        /**
         * @return array
         */
        public function getFilterMap()
        {
            return [
                // pro name uživatele se použije filtr s názvem name
                'name' => 'u.name',
                // pro id uživatele se použije filtr s názvem id
                'id' => 'u.id',
                'activated' => 'u.activated',
                'birthDate' => 'u.birthDate',
                'hairs' => 'u.hairs',
            ];
        }

        /**
         * @return array
         */
        public function getSorterMap()
        {
            return [
                // pro name uživatele se použije sorter s názvem name
                'name' => 'u.name',
            ];
        }

        /**
         * Vrací pole defaultních řazení
         *
         * @return array
         */
        public function getDefaultSort()
        {
            return [];
        }
    }

Filtrování a sortování query objektu podle dat z requestu
---------------------------------------------------------

.. sourcecode:: php

    <?php
    /* @var $displayCriteriaFactory \Imatic\Bundle\DataBundle\Request\Query\DisplayCriteriaFactory */
    $displayCriteriaFactory = $this->get('imatic_data.display_criteria_factory');

    $displayCriteria = $displayCriteriaFactory->createCriteria([
        'componentId' => 'componentFromRequest',
        'filter' => new UserFilter(),
    ]);

    // formulář filtrů
    $form = $displayCriteria->getFilter->getForm();

    // link na sortovani podle id
    // <a href="http://localhost?sorter[id]=asc">Sort by id</a>

Imatic\Bundle\DataBundle\Request\Query\DisplayCriteriaFactory
-------------------------------------------------------------

* Vytváří Filtery, Sortery a Pager z requestu (lze je přepsat pokud se předají jako parametr metodě createCriteria)
