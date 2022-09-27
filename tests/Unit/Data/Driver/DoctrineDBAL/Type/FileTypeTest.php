<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Driver\DoctrineDBAL\Type;

use Doctrine\DBAL\Types\Type;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Type\FileType;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FileTypeTest extends TestCase
{
    /**
     * @var FileType
     */
    private $fileType;
    private $platform;

    protected function setUp(): void
    {
        $this->platform = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        if (Type::hasType('file')) {
            Type::overrideType('file', 'Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Type\FileType');
        } else {
            Type::addType('file', 'Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Type\FileType');
        }

        $this->fileType = Type::getType('file');
        FileType::setBasePath('');
    }

    /**
     * @dataProvider filePathProvider
     */
    public function testPathsShouldBePreservedInDatabaseIfBasePathIsNotSpecified($givenPath)
    {
        $file = new File($givenPath, false);

        $actual = $this->fileType->convertToDatabaseValue($file, $this->platform);
        $this->assertEquals($givenPath, $actual);
    }

    public function filePathProvider()
    {
        return [
            ['file.txt'],
            ['/srv/www/project/uploads/file.txt'],
        ];
    }

    public function testPathsShouldBePreservedInFileIfBasePathIsNotSpecified()
    {
        vfsStreamWrapper::register();
        vfsStream::setup('root', null, [
            'uploads' => [
                'uploaded.txt' => 'content of the file',
            ],
        ]);

        $file = $this->fileType->convertToPHPValue(vfsStream::url('root/uploads/uploaded.txt'), $this->platform);
        $this->assertEquals(vfsStream::url('root/uploads/uploaded.txt'), $file->getPathname());
    }

    public function testPathShouldBeChangedInDatabaseAccordingToBasePath()
    {
        FileType::setBasePath('/srv/www/project');

        $file = new File('/srv/www/project/file.txt', false);

        $actual = $this->fileType->convertToDatabaseValue($file, $this->platform);
        $this->assertEquals('/file.txt', $actual);
    }

    public function testPathShouldBeChangedInFileAccordingToBasePath()
    {
        FileType::setBasePath(vfsStream::url('root/uploads'));

        vfsStreamWrapper::register();
        vfsStream::setup('root', null, [
            'uploads' => [
                'uploaded.txt' => 'content of the file',
            ],
        ]);

        $file = $this->fileType->convertToPHPValue('/uploaded.txt', $this->platform);
        $this->assertEquals(vfsStream::url('root/uploads/uploaded.txt'), $file->getPathname());
    }
}
