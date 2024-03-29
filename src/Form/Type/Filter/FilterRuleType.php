<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterRuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var FilterRule $rule */
        $rule = $options['filter_rule'];
        $operators = $rule->getOperators();
        $preferredOperator = $rule->getOperator();

        if (\count($operators) > 1) {
            $builder->add(
                'operator',
                ChoiceType::class,
                [
                    'choices' => \array_combine($operators, $operators),
                    'data' => $preferredOperator,
                    'translation_domain' => 'ImaticDataBundle',
                ]
            );
        }
        $builder->add(
            'value',
            $rule->getFormType(),
            \array_merge(
                $rule->getFormOptions(),
                [
                    'required' => false,
                    'property_path' => 'ruleValue',
                ]
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'filter_rule' => null,
            'data_class' => 'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule',
        ]);

        $resolver->setAllowedTypes(
            'filter_rule',
            'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule'
        );
    }
}
