<?php

namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class FilterRuleType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule',
            'empty_data' => function (FormInterface $form) {
                    $value = $form->get('value')->getData();
                    if (!$value) {
                        return;
                    }

                    $operator = null;
                    if ($form->has('operator')) {
                        $operator = $form->get('operator')->getData();
                    }

                    $class = $this->getFilterRuleClass();

                    return new $class($form->getName(), $value, $operator);
                }
        ]);
    }

    abstract protected function getFilterRuleClass();
}