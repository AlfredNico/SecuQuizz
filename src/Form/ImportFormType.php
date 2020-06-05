<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('import', FileType::class, [
            'label' => false,
            'multiple' => false,
            'mapped' => false,
            'required' => false,
            'empty_data'    =>  null,
            'required'      =>  FALSE,
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label'=>'Importer',
            'attr'=>['class'=>'btn btn-default'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
