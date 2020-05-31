<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('attached')
            ->add('texteComplementaire')
            ->add('autreTexte')
            ->add('etat')
            ->add('motif')
            ->add('users')
            ->add('competences')
            ->add('types')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
