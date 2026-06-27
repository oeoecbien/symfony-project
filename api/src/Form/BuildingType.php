<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Building;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('slug', TextType::class, ['required' => false])
            ->add('caste', TextType::class)
            ->add('strength', IntegerType::class)
            ->add('image', TextType::class, ['required' => false])
            ->add('price', IntegerType::class, ['required' => false])
            ->add('identifier', TextType::class, ['required' => false])
            ->add('creation', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Building::class,
            'csrf_protection' => false,
        ]);
    }
}
