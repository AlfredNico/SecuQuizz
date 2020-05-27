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
            ->add('parent', EntityType::class, [
                'class' => Families::class,
                'choice_label' => 'title',
                'label' => 'Parent'
            ])
            // ->add('parent', CollectionType::class, [
            //     'entry_type' => FamiliesType::class,
            //     'entry_options' => ['label' => false],
            // ])
            ->add('enfant')
            //->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Families::class,
        ]);
    }
}
