<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ApiCharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 3, max: 20),
                ],
                'help' => '3 a 20 caracteres',
            ])
            ->add('kind', ChoiceType::class, [
                'choices' => [
                    'Dame' => 'Dame',
                    'Seigneur' => 'Seigneur',
                    'Tourmenteur' => 'Tourmenteur',
                    'Tourmenteuse' => 'Tourmenteuse',
                ],
                'constraints' => [new NotBlank()],
            ])
            ->add('surname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(min: 3, max: 50),
                ],
                'help' => '3 a 50 caracteres',
            ])
            ->add('caste', TextType::class, [
                'required' => false,
                'constraints' => [new Length(min: 3, max: 20)],
                'help' => 'Optionnel, 3 a 20 caracteres si renseigne',
            ])
            ->add('knowledge', TextType::class, [
                'required' => false,
                'constraints' => [new Length(min: 3, max: 20)],
                'help' => 'Optionnel, ex. Sciences',
            ])
            ->add('intelligence', IntegerType::class, [
                'required' => false,
                'attr' => ['min' => 0, 'max' => 250],
            ])
            ->add('strength', IntegerType::class, [
                'required' => false,
                'label' => 'Force',
                'attr' => ['min' => 0, 'max' => 250],
            ])
            ->add('life', IntegerType::class, [
                'required' => false,
                'label' => 'Vie',
                'attr' => ['min' => 0, 'max' => 250],
            ])
            ->add('image', TextType::class, [
                'required' => false,
                'constraints' => [new Length(min: 5, max: 50)],
                'help' => 'Optionnel, ex. /seigneurs/noa.png (fichier dans public/images/images/ de l API)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
