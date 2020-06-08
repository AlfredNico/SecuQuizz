<?php

namespace App\Form;

use App\Entity\Answers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnswersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control' , 'placeholder' => 'reponse ...'
                ]
            ])
            ->add('isAnswer', ChoiceType::class, array(
                'choices' => array(
                    'OUI' => true,
                    'NON' => false
                ),
                'label' => 'Vraie réponse',
                'required' => true
            ));

        $builder->get('isAnswer')
            ->addModelTransformer(new CallbackTransformer(
                function ($property) {
                    return (string) $property;
                },
                function ($property) {
                    return (bool) $property;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answers::class,
        ]);
    }
}
