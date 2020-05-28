<?php

namespace App\Form;

use App\Entity\Families;
use App\Entity\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChoixniveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', EntityType::class, array(
                'class'         =>  Niveau::class,
                'choice_label'  =>  'title',
                'multiple'      =>  FALSE,
                'expanded'      =>  FALSE,
                'placeholder'   =>  '-- Choissir Niveau --',
                'empty_data'    =>  null,
                'required'      =>  FALSE,
            ))
        ;
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Families::class,
    //     ]);
    // }
}
