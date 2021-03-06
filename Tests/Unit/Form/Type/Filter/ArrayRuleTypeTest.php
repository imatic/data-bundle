<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Unit\Form\Type;

use Imatic\Bundle\DataBundle\Form\Type\Filter\ArrayRuleType;
use Symfony\Component\Form\Test\TypeTestCase;

class ArrayRuleTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $form = $this->factory->create(ArrayRuleType::class);
        $form->submit('a, b,c ,  d   ');
        $this->assertEquals(['a', 'b', 'c', 'd'], $form->getData());
    }
}
