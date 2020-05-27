<?php

namespace App\Form;

use App\Entity\Families;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamiliesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            //->add('users')
            ->add('parent', EntityType::class, array(
                'class'         =>  Families::class,
                'choice_label'  =>  'title',
                'multiple'      =>  FALSE,
                'expanded'      =>  FALSE,
                'placeholder'   =>  '-- Choissir Article --',
                'empty_data'    =>  null,
                'required'      =>  FALSE,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Families::class,
        ]);
    }
}
