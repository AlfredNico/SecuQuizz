<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email', EmailType::class, [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Adresse Mail'
                    ]
                ])
                // ->add('plainPassword', RepeatedType::class, array(
                //     'type' => PasswordType::class,
                //     'first_options' => array('label' => 'Mot de passe'),
                //     'second_options' => array('label' => 'Confirmation du mot de passe'),
                // ))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array(
                        'label' => 'Mot de passe',
                        'attr' => [
                            'placeholder'=> 'Mot de passe',
                            'class' => 'form-control'
                        ],
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Valider le mot de passe',
                            ]),
                            new Length([
                                'min' => 6,
                                'minMessage' => 'Your password should be at least {{ limit }} characters',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]),
                        ],
                    ),
                    'second_options' => array(
                        'label' => 'Confirmation du mot de passe',
                        'attr' => [
                            'placeholder'=> 'Confirmer le Mot de passe',
                            'class' => 'form-control'
                        ],
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Valider la confirmation mot de passe',
                            ]),
                            new Length([
                                'min' => 6,
                                'minMessage' => 'Your password should be at least {{ limit }} characters',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]),
                        ],
                    )
                ))
                ->add('submit', SubmitType::class, ['label'=>'Envoyer', 'attr'=>['class'=>'btn-primary btn-block']])
        ;
    }
}