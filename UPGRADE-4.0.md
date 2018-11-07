UPGRADE FROM 3.x to 4.0
=======================

Filters
-------

* The `FilterFactory::setFilters()` method was removed.

* The `FilterFactory::addFilters()` method was removed.

* Alias attribute of `imatic_data.filter` tag was removed. Use service id or service alias as filter name instead.

Handlers
--------

* Service `imatic_data.command_handler_repository` was removed. Use `HandlerRepositoryInterface` instead.

* Alias attribute of `imatic_data.handler` tag was removed. Use service id or service alias as handler name instead.

* The following handler tags have been removed; use their fully-qualified class name instead:

    - `imatic_data.generic_create` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\CreateHandler`
    - `imatic_data.generic_delete` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\DeleteHandler`
    - `imatic_data.generic_edit` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\EditHandler`
    - `imatic_data.doctrine_dbal.generic_create` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\CreateHandler`
    - `imatic_data.doctrine_dbal.generic_create_or_edit` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\CreateOrEditHandler`
    - `imatic_data.doctrine_dbal.generic_delete` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\DeleteHandler`
    - `imatic_data.doctrine_dbal.generic_edit` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\EditHandler`
    - `imatic_data.doctrine_dbal.generic_soft_delete` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\SoftDeleteHandler`

Services
--------

* The following service aliases have been removed; use their fully-qualified class name instead:
    
    - `imatic_data.array_display_criteria_factory` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory`
    - `imatic_data.array_rule_type` use `alias: Imatic\Bundle\DataBundle\Form\Type\Filter\ArrayRuleType`
    - `imatic_data.command_executor` use `Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface`
    - `imatic_data.decorated_command_handler_repository` use `Imatic\Bundle\DataBundle\Data\Command\HandlerRepositoryInterface`
    - `imatic_data.display_criteria_factory` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory`
    - `imatic_data.display_criteria_query_builder` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderDelegate`
    - `imatic_data.doctrine.between_operator_processor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\BetweenOperatorProcessor`
    - `imatic_data.doctrine.callback_processor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\CallbackProcessor`
    - `imatic_data.doctrine.contains_operator_processor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\ContainsOperatorProcessor`
    - `imatic_data.doctrine.dbal.query_executor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryExecutor`
    - `imatic_data.doctrine.dbal.query_executor_delegate` use `Imatic\Bundle\DataBundle\Data\Query\QueryExecutorDelegate`
    - `imatic_data.doctrine.dbal.query_executor_factory` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryExecutorFactory`
    - `imatic_data.doctrine.default_rule_processor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\DefaultRuleProcessor`
    - `imatic_data.doctrine.display_criteria_query_builder` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\DisplayCriteriaQueryBuilder`
    - `imatic_data.doctrine.empty_operator_processor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\EmptyOperatorProcessor`
    - `imatic_data.doctrine.in_not_in_operator_processor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\InNotInOperatorProcessor`
    - `imatic_data.doctrine.not_between_operator_processor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\NotBetweenOperatorProcessor`
    - `imatic_data.doctrine.object_manager` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ObjectManager`
    - `imatic_data.doctrine.query_executor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryExecutor`
    - `imatic_data.doctrine.rule_boolean_processor` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\RuleBooleanProcessor`
    - `imatic_data.driver_repository` use `Imatic\Bundle\DataBundle\Data\Driver\DriverRepository`
    - `imatic_data.driver.command.create` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\CreateHandler`
    - `imatic_data.driver.command.delete` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\DeleteHandler`
    - `imatic_data.driver.command.edit` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\EditHandler`
    - `imatic_data.driver.doctrine_dbal.command.create` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\CreateHandler`
    - `imatic_data.driver.doctrine_dbal.command.create_or_edit` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\CreateOrEditHandler`
    - `imatic_data.driver.doctrine_dbal.command.delete` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\DeleteHandler`
    - `imatic_data.driver.doctrine_dbal.command.edit` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\EditHandler`
    - `imatic_data.driver.doctrine_dbal.command.soft_delete` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\SoftDeleteHandler`
    - `imatic_data.driver.doctrine_dbal.record_iterator` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\RecordIterator`
    - `imatic_data.driver.doctrine_dbal.result_iterator_factory` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultIteratorFactory`
    - `imatic_data.driver.doctrine_dbal.schema` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema\Schema`
    - `imatic_data.driver.doctrine_orm.record_iterator` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\RecordIterator`
    - `imatic_data.driver.doctrine_orm.result_iterator_factory` use `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ResultIteratorFactory`
    - `imatic_data.extjs_display_criteria_reader` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\ExtJsReader`
    - `imatic_data.filter_factory` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory`
    - `imatic_data.filter_rule_processor` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRuleProcessorDelegate`
    - `imatic_data.form.extension.query_object` use `Imatic\Bundle\DataBundle\Form\Extension\EntityTypeQueryObjectExtension`
    - `imatic_data.pager_factory` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory`
    - `imatic_data.query_executor` use `Imatic\Bundle\DataBundle\Data\Query\QueryExecutor`
    - `imatic_data.request_query_display_criteria_reader` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\RequestQueryReader`
    - `imatic_data.display_criteria_reader` use `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\DisplayCriteriaReader`
