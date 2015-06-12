<?php

namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class FilterRuleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $rule FilterRule */
        $rule = $options['filter_rule'];
        $operators = $rule->getOperators();
        $preferredOperator = $rule->getOperator();

        if (count($operators) > 1) {
            $builder->add(
                'operator',
                'genemu_jqueryselect2_choice', [
                    'choices' => array_combine($operators, $operators),
                    'data' => $preferredOperator,
                    'translation_domain' => 'ImaticDataBundle'
                ]
            );
        }
        $builder->add(
            'value',
            $rule->getFormType(),
            array_merge($rule->getFormOptions(), ['required' => false, 'mapped' => false])
        );

        // only map value to the rule if it is valid
        $builder->get('value')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();

            if ($form->isValid()) {
                $rule = $form->getParent()->getData();

                 if ($rule instanceof FilterRule) {
                     $rule->setValue($form->getData());
                 }
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'filter_rule' => null,
            'data_class' => 'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule',
        ]);
        $resolver->setAllowedTypes([
            'filter_rule' => 'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule'
        ]);
    }

    public function getName()
    {
        return 'imatic_filter_rule';
    }
}
