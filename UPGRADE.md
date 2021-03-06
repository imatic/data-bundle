# 3.2.0

## Added

### Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query\SoftDeleteQuery

- Query now supports removal of multiple records at once by providing array as it's second argument.

### Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\SoftDeleteHandler

- Handler now supports removal of multiple records at once by using `ids` parameter.

### Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\CreateHandler

- Parameter `class` is now optional.

## Deprecated

### Number of services were renamed

- `imatic_data.doctrine.object_manager` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ObjectManager`
- `imatic_data.doctrine.query_executor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryExecutor`
- `imatic_data.driver.command.edit` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\EditHandler`
- `imatic_data.driver.command.create` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\CreateHandler`
- `imatic_data.driver.command.delete` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\DeleteHandler`
- `imatic_data.driver.doctrine_orm.record_iterator` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\RecordIterator`
- `imatic_data.driver.doctrine_orm.result_iterator_factory` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\ResultIteratorFactory`
- `imatic_data.driver.doctrine_dbal.command.create` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\CreateHandler`
- `imatic_data.doctrine.dbal.query_executor_factory` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryExecutorFactory`
- `imatic_data.driver.doctrine_dbal.command.edit` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\EditHandler`
- `imatic_data.driver.doctrine_dbal.command.create_or_edit` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\CreateOrEditHandler`
- `imatic_data.driver.doctrine_dbal.command.delete` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\DeleteHandler`
- `imatic_data.driver.doctrine_dbal.command.soft_delete` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\SoftDeleteHandler`
- `imatic_data.driver.doctrine_dbal.schema` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Schema\Schema`
- `imatic_data.driver.doctrine_dbal.record_iterator` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\RecordIterator`
- `imatic_data.doctrine.dbal.query_executor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\QueryExecutor`
- `imatic_data.driver.doctrine_dbal.result_iterator_factory` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultIteratorFactory`
- `imatic_data.doctrine.display_criteria_query_builder` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\DisplayCriteriaQueryBuilder`
- `imatic_data.doctrine.callback_processor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\CallbackProcessor`
- `imatic_data.doctrine.rule_boolean_processor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\RuleBooleanProcessor`
- `imatic_data.doctrine.between_operator_processor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\BetweenOperatorProcessor`
- `imatic_data.doctrine.not_between_operator_processor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\NotBetweenOperatorProcessor`
- `imatic_data.doctrine.contains_operator_processor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\ContainsOperatorProcessor`
- `imatic_data.doctrine.empty_operator_processor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\EmptyOperatorProcessor`
- `imatic_data.doctrine.in_not_in_operator_processor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\InNotInOperatorProcessor`
- `imatic_data.doctrine.default_rule_processor` -> `Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\DefaultRuleProcessor`
- `imatic_data.command_executor` -> `Imatic\Bundle\DataBundle\Data\Command\CommandExecutorInterface`
- `imatic_data.decorated_command_handler_repository` -> `Imatic\Bundle\DataBundle\Data\Command\HandlerRepository`
- `imatic_data.command_handler_repository` -> `Imatic\Bundle\DataBundle\Data\Command\ContainerHandlerRepository`
- `imatic_data.driver_repository` -> `Imatic\Bundle\DataBundle\Data\Driver\DriverRepository`
- `imatic_data.query_executor` -> `Imatic\Bundle\DataBundle\Data\Query\QueryExecutor`
- `imatic_data.pager_factory` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory`
- `imatic_data.display_criteria_query_builder` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderInterface`
- `imatic_data.filter_rule_processor` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRuleProcessorDelegate`
- `imatic_data.request_query_display_criteria_reader` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\RequestQueryReader`
- `imatic_data.extjs_display_criteria_reader` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\ExtJsReader`
- `imatic_data.display_criteria_factory` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory`
- `imatic_data.array_display_criteria_factory` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory`
- `imatic_data.form.extension.query_object` -> `Imatic\Bundle\DataBundle\Form\Extension\EntityTypeQueryObjectExtension`
- `imatic_data.filter_factory` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterFactory`
- `imatic_data.array_rule_type` -> `Imatic\Bundle\DataBundle\Form\Type\Filter\ArrayRuleType`
- `imatic_data.display_criteria_reader` -> `Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\DisplayCriteriaReader`
