<?php

namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $rule FilterRule */
        foreach ($options['filter'] as $rule) {
            $builder->add(
                $rule->getName(),
                new FilterRuleType(), [
                    'filter_rule' => $rule,
                    'property_path' => sprintf('[%s]', $rule->getName()),
                ]);
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter',
            'csrf_protection' => false,
            'filter' => null,
        ]);
    }

    public function getName()
    {
        return 'imatic_filter';
    }
}
