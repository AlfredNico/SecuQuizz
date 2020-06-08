<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'titre question ...'
                ]
            ])
            // ->add('attached')
            ->add('texteComplementaire', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Texte Complementaire ...'
                ]
            ])
            ->add('autreTexte', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Autre Text ...'
                ]
            ])
            ->add('etat')
            ->add('motif', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Motif ... '
                ]
            ])
            //->add('users')
            ->add('competences')
            ->add('types')
            // import une image dans nos base de donnéd
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Image ...'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Valider', 
                'attr'=>['class'=>'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
