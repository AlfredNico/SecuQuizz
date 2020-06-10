<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuestionsValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Valider' => true,
                    'Refuser' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('motif', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Modif ...'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
