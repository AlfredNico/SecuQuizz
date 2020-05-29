<?php

namespace App\Form;

use App\Entity\Families;
use App\Entity\Niveau;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamiliesType2 extends AbstractType
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
                'placeholder'   =>  '-- Choissir Niveau --',
                'empty_data'    =>  null,
                'required'      =>  FALSE,
                'choices_as_values' => true,
                'data' => 'friend',
                'choice_attr' => function ($key, $val, $index) {
                    $disabled = false;

                    // set disabled to true based on the value, key or index of the choice...

                    return $disabled ? ['disabled' => 'disabled'] : [];
                },
            ))
            ->add('niveau', EntityType::class, array(
                'class'         =>  Users::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles = :ROLE_EDITOR')
                        ->orderBy('u.email', 'ASC');
                },
                'choice_label'  =>  'title',
                'multiple'      =>  FALSE,
                'expanded'      =>  FALSE,
                'placeholder'   =>  '-- Choissir Admin User --',
                'empty_data'    =>  null,
                'required'      =>  FALSE,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Families::class,
        ]);
    }
}
