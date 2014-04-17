<?php

namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterType extends AbstractType
{
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @param FilterInterface $filter
     */
    public function __construct(FilterInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var $rule FilterRule */
        foreach ($this->filter as $rule) {
            $fieldName = $rule->getName();
            $fieldType = $rule->getFormType();
            $fieldOptions = array_merge($rule->getFormOptions(), ['mapped' => false, 'required' => false]);
            $choices = $rule->getOperators();

            $field = $builder->create($fieldName, null, ['compound' => true, 'mapped' => false]);
            if (count($choices) > 1) {
                $field->add('operator', 'choice', [
                        'mapped' => false,
                        'choices' => array_combine($choices, $choices),
                        'translation_domain' => 'ImaticDataBundle']
                );
            }

            if ($rule->hasDefault()) {
                $fieldOptions['data'] = $rule->getDefault();
            }

            $field->add('value', $fieldType, $fieldOptions);
            $builder->add($field);
        }

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $this->bindFilter($event->getForm());
            }
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter',
            'csrf_protection' => false,
            'translation_domain' => $this->filter->getTranslationDomain(),
            'empty_data' => function () {
                    return $this->filter;
                }
        ]);
    }

    public function getName()
    {
        return 'filter';
    }

    protected function bindFilter(FormInterface $form)
    {
        /** @var $rule FilterRule */
        foreach ($this->filter as $rule) {
            $field = $form->get($rule->getName());
            if ($field->has('value') && !is_null($data = $field->get('value')->getData())) {
                $operator = null;
                if ($field->has('operator')) {
                    $operator = $field->get('operator')->getData();
                }
                $rule->bind($data, $operator);
            }
        }
    }
}