# 3.2.0

## Added

### Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Query\SoftDeleteQuery

- Query now supports removal of multiple records at once by providing array as it's second argument.

### Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Command\SoftDeleteHandler

- Handler now supports removal of multiple records at once by using `ids` parameter.

### Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\Command\CreateHandler

- Parameter `class` is now optional.
