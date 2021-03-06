<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter',
            'csrf_protection' => false,
            'filter' => null,
        ]);
    }
}
