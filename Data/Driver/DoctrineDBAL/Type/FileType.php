<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FileType extends Type
{
    const FILE = 'file';

    protected static $basePath = '';

    public function getName()
    {
        return self::FILE;
    }

    public function canRequireSQLConversion()
    {
        return true;
    }

    /**
     * @param File|null        $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (static::$basePath !== '' && \strpos($value->getPathname(), static::$basePath) !== 0) {
            throw new \LogicException(\sprintf('Invalid path or "basePath" ("%s") configuration', static::$basePath));
        }

        return \substr($value->getPathname(), \strlen(static::$basePath));
    }

    /**
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return File|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new File(static::$basePath . $value);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public static function setBasePath($path)
    {
        static::$basePath = $path;
    }
}
