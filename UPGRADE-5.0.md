UPGRADE FROM 4.x to 5.0.0-alpha.2
=================================

Dbal query executor (`Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL`) should now return results with proper types. If you depend on old behaviour, you should check all your queries. Old behaviour can be temporarily turned on by aliasing deprecated result normalizer:
```xml
<service id="Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer\ResultNormalizer" alias="Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\ResultNormalizer\DeprecatedResultNormalizer"/>
```
