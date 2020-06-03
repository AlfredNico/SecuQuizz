<?php

namespace App\Form;

use App\Entity\Types;
use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numc')
            ->add('title')
            ->add('texteComplementaire')
            ->add('autreTexte')
            ->add('etat')
            // ->add('motif')
            ->add('pj', FileType::class, [
                'label' => 'Pièce jointe (Photos ou Video)',
                'mapped' => false,
                'required' => false
            ])
            ->add('type', EntityType::class, array('class' => Types::class, 'choice_label' => 'title'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
