<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Unit\Form\DataTransformer;

use Imatic\Bundle\DataBundle\Form\DataTransformer\ArrayToStringTransformer;
use PHPUnit\Framework\TestCase;

class ArrayToStringTransformerTest extends TestCase
{
    /**
     * @dataProvider reverseTransformDataProvider
     */
    public function testReverseTransform($value, $expectedValue)
    {
        $transformer = new ArrayToStringTransformer();
        $this->assertEquals($expectedValue, $transformer->reverseTransform($value));
    }

    public function reverseTransformDataProvider(): array
    {
        return [
            [
                null,
                [],
            ],
            [
                '',
                [],
            ],
            [
                '0',
                [0],
            ],
            [
                0,
                [0],
            ],
            [
                '1,2,some text',
                [1, '2', 'some text'],
            ],
            [
                [1, '2', 'some text'],
                [1, '2', 'some text'],
            ],
        ];
    }

    /**
     * @dataProvider transformDataProvider
     */
    public function testTransform($value, $expectedValue)
    {
        $transformer = new ArrayToStringTransformer();
        $this->assertEquals($expectedValue, $transformer->transform($value));
    }

    public function transformDataProvider(): array
    {
        return [
            [
                null,
                '',
            ],
            [
                [],
                '',
            ],
            [
                [1, ' 2 ', ' some text '],
                '1,2,some text',
            ],
        ];
    }
}
