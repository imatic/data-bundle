<?php
namespace Imatic\Bundle\DataBundle\Form\Extension;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\QueryObjectInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type theme extension.
 *
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
class EntityTypeQueryObjectExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'query_object' => null,
            'query_builder' => function (Options $options) {
                if (null !== $options['query_object']) {
                    return $options['query_object']->build($options['em']);
                }
            },
        ]);

        $resolver->setAllowedTypes(
            'query_object',
            ['null', QueryObjectInterface::class]
        );
    }

    public function getExtendedType()
    {
        return EntityType::class;
    }
}
