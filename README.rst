ImaticDataBundle
================

Data API
========

Data/Model (data a jejich zpracování)
-------------------------------------
Práce s daty musí být sjednocena do jedné vrstvy/jednoho místa, kde probíhají všechny změny.
Není možné aby bylo čtení a změny dat napříč celou aplikací.
Vznikají potom duplicity a chyby, protože není kontrola co kde je.

Query (čtení dat)
^^^^^^^^^^^^^^^^^
Při čtení dat je nutné znát několik skupin kritérií:

- parametry pro čtení (např ID záznamu, nebo název kategorie produktů v případě výpisu)
- vnitřní logika čtení jako připojení jiných dat, omezení smazaných nebo neaktivních záznamů atp.
- přístupová práva k záznamu/záznamům
- v případě výpisu i omezení zobrazení (filtr, řazení, krokování)

K tomu většinou slouží repository, ve které se ukládají jednotlivé dotazy do metod s parametry.
Repository mají ale několik nevýhod:

- dotaz je metoda nikoliv objekt, nemožnost konfigurace jinak nez parametry dané metody
- při větším počtu dotazů vzniká velké množství metod a repository narůstá do nepřehledně velké třídy
- všechen kód bývá pouze v jedné dlouhé metodě
- nemožnost pracovat s dotazem dál, repository vrací rovnou pole záznamů

Doporučovanou alternativou je tzv. **QueryObject**. Každý dotaz je zabalen do objektu s daným rozhraním.
Výhody jsou:

- **každý dotaz je sémanticky pojmenován a uložen ve své třídě, kód je přehledně rozdělen a bez duplicit**
- možnost konfigurace dotazu jak přes parametry konstruktoru (povinné) tak přes metody (doplňující nastavení)
- třídu s dotazem je možné vnitřně rozdělit do dalších metod pro lepší čitelnost
- query objekt vrací instanci QueryBuilder, je tedy možné pracovat s dotazem dál (omezení zobrazení jako filtr apod)

Command (modifikace dat)
^^^^^^^^^^^^^^^^^^^^^^^^
Pro modifikaci dat lze použít QueryObject stejně dobře jako pro čtení. V určitých případech to je i doporučené.
Většinou si ale s jeho logikou nevystačíme, protože je třeba komplexnější změna, která se nevejde do jednoho příkazu.
Nemusíme také chtít hromadné mazání nebo aktualizaci (QueryObject), protože potřebujeme volat události nad objekty atp.

Změny dat se většinou realizují třemi způsoby:

- form editace (data jsou načtena z databáze, vložena do formuláře a následně modifikována podle uživatelova vstupu)
- batch akce (hromadné změny dat, například mazání více záznamů nad jejich výpisem)
- patch akce (většinou změna jednoho nebo více atributů záznamu, například deaktivace uživatele z kontextového menu)

Každý způsob má jinak definovaný vstup viz sekce command v části request.
Pro veškeré změny dat a další operace se používá tzv **Handler**, který vystupuje jako aplikační fasáda.

Charakteristika handleru:

- **konkrétní handler reprezentuje jednu pojmenovanou operaci (USE CASE) v aplikaci**
- handler je možné volat jak z controlleru, tak přes CLI command, web service atp
- handler v sobě obsahuje transakční logiku (nikde jinde nesmí být transakce)
- handler je možno volat přes MessageQueue, jeho command je serializovatelný
- místo handleru je možné v controlleru volat rovnou MessageQueue a nechat zpracování na později
- handler je registrován do DI kontejneru jako služba a může mít různé závislosti
- každý způsob modifikace dat (form, batch, ...) má pro handler odlišné rozhraní
- handler by měl mít minimální závislost na frameworku a jeho službách (výjimky: EventDispatcher, SecurityContext)
- handler v produkčním prostředí nevyhazuje vyjímky, proto je potřeba hlídat CommandResult který vrací handler po svém vykonání

Doménové služby
"""""""""""""""

Ne vždycky se vejde nebo hodí všechna logika do entit. Jsou situace kdy je lepší danou logiku oddělit do zvláštní služby.


Datové služby pro čtení a modifikaci dat
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Služba, která má k dispozici entity manager a přes kterou se data načítají a mění.
Její API umožňuje:

- načíst a měnit data pomocí QueryObject
- spočítat počet záznamů podle QueryObject
- flush dat
- persist dat

Filtrování, krokování a řazení výsledků při čtení dat je zajištěno automaticky.
Služba doplňuje DisplayCriteria ke QueryBuilder vráceného z QueryObject.


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
        Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler\UserDeactivateHandler:
            arguments:
                - @imatic_data.query_executor
                - @imatic_data.object_manager
            tags:
                - { name: imatic_data.handler }

Provedení commandu
------------------

.. sourcecode:: php

    <?php

    use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Handler\UserDeactivateHandler;

    $id = 3;

    // vytvoří se Command, kterému se jako první argument předá trida handleru
    // a jako druhý argument se pak předá pole parametrů
    $command = new Command(UserDeactivateHandler::class, ['id' => $id]);

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

Imatic\\Bundle\\DataBundle\\Request\\Query\\DisplayCriteriaFactory
------------------------------------------------------------------

* Vytváří Filtery, Sortery a Pager z requestu (lze je přepsat pokud se předají jako parametr metodě createCriteria)


Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\Schema\\Schema
----------------------------------------------------------------------

* třída usnadňující práci s doctrine dbal
* automaticky escapuje názvy sloupců a tabulky, takže lze používat i rezervovaná slova
* získá typ pro každý sloupec, takže se např. DateTime automaticky převede na databázovou hodnotu

.. sourcecode:: php

   <?php
       
   $queryData = $this->schema->getQueryData($table = 'user', $data = [
       'name' => 'John Doe',
       'score' => 20,
   ]);

   $this->connection->insert($queryData->getTable(), $queryData->getData(), $queryData->getTypes());

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\Sql\\Sql
----------------------------------------------------------------

* třída umožňující vytvářet dotazy nezávisle na použíté databázi

.. sourcecode:: php

   <?php

   $query = sprintf('SELECT  u.id AS id %s AS full_name FROM user', Sql::concat([
       'u.first_name', ' ', 'u.last_name',
   ], $this->connection);

Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineDBAL\\Type\\FileType
----------------------------------------------------------------------

* type do doctrine umožňující ukládat soubory do db (do db se uloží pouze cesta)
  
Imatic\\Bundle\\DataBundle\\Data\\Driver\\DoctrineORM\\Command\\RecordIterator
------------------------------------------------------------------------------

* používá se u batch akcí pro iteraci jednotlivými záznamy/idčky

.. sourcecode:: php

        <?php

        public function handle(CommandInterface $command) {
            // iterate through ids
            $idCallback = function($id)) {
                echo sprintf("Processing user with id %s", $id);

                return CommandResult::success();
            };
            $recordIteratorIdArgs = new RecordIteratorArgs($command, new UserListQuery(), $idCallback);
            $this->recordIterator->eachIdentifier($recordIteratorIdArgs);

            // iterate through objects
            $userCallback = function(User $user) {
                echo sprintf("Processing user %s", $user->getFullName());

                return CommandResult::success();
            };
            $recordIteratorUserArgs = new RecordIteratorArgs($command, new UserListQuery(), $userCallback);
            $this->recordIterator->each($recordIteratorUserArgs);
        }

Předpřipravené command handlery
-------------------------------

 * pro jednoduché operace lze u jednotlivých driverů nalézt základní command handlery
 * např. pro DoctrineORM jsou to: create, edit, delete ("src/Data/Driver/DoctrineORM/Command/")

Dořešit
-------
Uložené filtry, zobrazení apod.

Pro jednotlivé třídy entit je možné registrovat tzv filtry.
Filtry jsou dvojího druhu:

- filtry umožňující automaticky doplnit například nějaké kritérium dotazu, které by se jinak opakovalo ve všech dotazech
  Tímto způsobem je možné například hlídat přístupová oprávnění podle nějakého atributu přihlášeného uživatele.
- filtry umožňující projít načtená data a provést nějakou modifikaci
  Tímto způsobem je možné například hlídat přístupová oprávnění podle nějakého atributu přihlášeného uživatele.
