<?php

namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Form\DataTransformer\ArrayToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ArrayRuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new ArrayToStringTransformer());
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'imatic_array_rule';
    }
}
