<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var FilterRule $rule */
        foreach ($options['filter'] as $rule) {
            $builder->add(
                $rule->getName(),
                FilterRuleType::class,
                [
                    'filter_rule' => $rule,
                    'property_path' => "[{$rule->getName()}]",
                ]
            );
        }
        $builder->add('clearFilter', SubmitType::class);
        $builder->add('defaultFilter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
            'csrf_protection' => false,
            'filter' => null,
        ]);
    }
}
