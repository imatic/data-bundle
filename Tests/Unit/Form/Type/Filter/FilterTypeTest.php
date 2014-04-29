<?php

namespace Imatic\Bundle\DataBundle\Tests\Unit\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\NumberRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\TextRule;
use Imatic\Bundle\DataBundle\Form\Type\Filter\FilterType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Forms;
use Genemu\Bundle\FormBundle\Form\JQuery\Type\Select2Type;

class FilterTypeTest extends TypeTestCase
{
    protected function setUp()
    {
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addType(new Select2Type('choice'))
            ->getFormFactory();
    }

    public function testFormSubmitReturnCorrectFilter()
    {
        $filterRule1 = new TextRule('field1');
        $filterRule2 = new NumberRule('field2');

        $filter = new Filter();
        $filter->add($filterRule1);
        $filter->add($filterRule2);

        $type = new FilterType();
        $form = $this->factory->create($type, $filter, ['filter' => $filter]);

        $form->submit([
            'field1' => [
                'value' => 'text'
            ],
            'field2' => [
                'value' => 100
            ],
        ]);

        /** @var Filter $data */
        $data = $form->getData();

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertInstanceOf('Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter', $data);
        $this->assertEquals($filterRule1, $data->get('field1'));
        $this->assertEquals($filterRule2, $data->get('field2'));

        $this->assertEquals('text', $data->get('field1')->getValue());
        $this->assertEquals(100, $data->get('field2')->getValue());
    }

    public function testFilterRuleDefaultValueSetsFormData()
    {
        $filterRule1 = new TextRule('field1');
        $filterRule1->setValue('default text');

        $filter = new Filter();
        $filter->add($filterRule1);

        $type = new FilterType();
        $form = $this->factory->create($type, $filter, ['filter' => $filter]);
        $view = $form->createView();
        $this->assertEquals('default text', $form->get('field1')->get('value')->getData());
        $this->assertEquals('default text', $view->children['field1']->vars['form']->children['value']->vars['value']);
    }
}
