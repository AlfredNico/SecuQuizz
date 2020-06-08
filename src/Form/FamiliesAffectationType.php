<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Niveau;
use App\Entity\Families;
use Doctrine\ORM\EntityRepository;
use App\Repository\UsersRepository;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Proxies\__CG__\App\Entity\Users as EntityUsers;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamiliesAffectationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, array(
                'class' => EntityUsers::class,
                'choice_label' => 'email',
                'multiple' => false,
                'query_builder' => function (UsersRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.roles', 'ASC')
                        ->where('u.article is NULL')
                        // ->setParameter('article', is_null())
                        ->andwhere('u.roles LIKE :role')
                        ->setParameter('role', '%"' . 'ROLE_EDITOR' . '"%');
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Families::class,
        ]);
    }
}
