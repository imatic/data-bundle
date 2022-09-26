<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Form\Type;

use Imatic\Bundle\DataBundle\Form\Type\Filter\FilterType;
use Symfony\Component\Form\FormBuilderInterface;

class UserFilterType extends FilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', 'imatic_data_text_filter', ['required' => false, 'mapped' => false]);

        parent::buildForm($builder, $options);
    }

    public function getName(): string
    {
        return 'user_filter';
    }
}
