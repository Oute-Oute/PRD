<?php

namespace App\Form;

use App\Entity\MaterialResource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialResourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('materialresourcename')
            ->add('available')
            ->add('categorymaterialresource')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MaterialResource::class,
        ]);
    }
}
