<?php
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject;

use Imatic\Bundle\TestingBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    /**
     * @return string
     */
    protected static function getKernelClass()
    {
        return 'Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\TestKernel';
    }
}
