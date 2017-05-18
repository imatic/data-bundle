<?php
namespace Imatic\Bundle\DataBundle\Tests\Unit\Util;

use Imatic\Bundle\DataBundle\Utils\QueryUtil;

class QueryUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateParameterName()
    {
        $this->assertSame('param1', QueryUtil::generateParameterName());
        $this->assertSame('param2', QueryUtil::generateParameterName());
        $this->assertSame('customPrefix3', QueryUtil::generateParameterName('customPrefix'));
        $this->assertSame('customPrefix4', QueryUtil::generateParameterName('customPrefix'));
    }
}
