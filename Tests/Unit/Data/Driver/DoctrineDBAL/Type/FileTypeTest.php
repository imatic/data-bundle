<?php

namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Driver\DoctrineDBAL\Type;

use Doctrine\DBAL\Types\Type;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineDBAL\Type\FileType;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\File\File;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class FileTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FileType
     */
    private $fileType;
    private $platform;

    protected function setUp()
    {
        $this->platform = $this->getMockBuilder('Doctrine\DBAL\Platforms\AbstractPlatform')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;

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
        $file = new File($givenPath, $checkPath = false);

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
            ]
        ]);

        $file = $this->fileType->convertToPHPValue(vfsStream::url('root/uploads/uploaded.txt'), $this->platform);
        $this->assertEquals(vfsStream::url('root/uploads/uploaded.txt'), $file->getPathname());
    }

    public function testPathShouldBeChangedInDatabaseAccordingToBasePath()
    {
        FileType::setBasePath('/srv/www/project');

        $file = new File('/srv/www/project/file.txt', $checkPath = false);

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
            ]
        ]);

        $file = $this->fileType->convertToPHPValue('/uploaded.txt', $this->platform);
        $this->assertEquals(vfsStream::url('root/uploads/uploaded.txt'), $file->getPathname());
    }
}
