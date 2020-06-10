<?php

namespace App\Form;

use App\Entity\Niveau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NiveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Titre Type ...'
                ]
            ])
            ->add('ordre', [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Ordre Type ...'
                ]
            ]
        )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Niveau::class,
        ]);
    }
}
