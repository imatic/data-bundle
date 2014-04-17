<?php

namespace Imatic\Bundle\DataBundle\Tests\Unit\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule\FilterRuleNumber;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule\FilterRuleText;
use Imatic\Bundle\DataBundle\Form\Type\Filter\FilterType;
use Symfony\Component\Form\Test\TypeTestCase;

class FilterTypeTest extends TypeTestCase
{
    public function testFormSubmitReturnCorrectFilter()
    {
        $filterRule1 = new FilterRuleText('field1');
        $filterRule2 = new FilterRuleNumber('field2');

        $filter = new Filter();
        $filter->addRule($filterRule1);
        $filter->addRule($filterRule2);

        $type = new FilterType($filter);
        $form = $this->factory->create($type);

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
        $this->assertEquals($filterRule1, $data->get(0));
        $this->assertEquals($filterRule2, $data->get(1));

        $this->assertEquals('text', $data->get(0)->getValue());
        $this->assertEquals(100, $data->get(1)->getValue());
    }

    public function testFilterRuleDefaultValueSetsFormData()
    {
        $filterRule1 = new FilterRuleText('field1');
        $filterRule1->setDefault('default text');

        $filter = new Filter();
        $filter->addRule($filterRule1);

        $type = new FilterType($filter);
        $form = $this->factory->create($type);
        $view = $form->createView();
        $this->assertEquals('default text', $form->get('field1')->get('value')->getData());
        $this->assertEquals('default text', $view->children['field1']->vars['form']->children['value']->vars['value']);
    }
}