<?php

namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRuleText;
use Symfony\Component\Form\FormBuilderInterface;

class TextRuleType extends FilterRuleType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array_combine(FilterRuleText::getOperators(), FilterRuleText::getOperators());
        $builder
            ->add('value', 'text', ['required' => false, 'mapped' => false])
            ->add('operator', 'choice', ['choices' => $choices, 'required' => true, 'mapped' => false]);
    }

    public function getName()
    {
        return 'imatic_data_text_filter';
    }

    protected function getFilterRuleClass()
    {
        return 'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRuleText';
    }
}