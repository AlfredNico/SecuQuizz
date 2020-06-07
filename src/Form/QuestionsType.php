<?php

namespace App\Form;

use App\Entity\Types;
use App\Entity\Questions;
use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $article = $options['article'];
        $builder
            ->add('competences', EntityType::class, array(
                'class' => Competence::class,
                'choice_label' => 'title',
                'multiple' => true,
                // 'group_by' => 'article.id',
                'query_builder' => function (CompetenceRepository $competencecepository)  use ($article) {
                    return $competencecepository->createQueryBuilder('c')
                        // ->addSelect('a')
                        // ->innerJoin('c.article', 'a')
                        // ->where('a.id')
                        // ->orderBy('a.id, c.id');
                        // ->andWhere('w.agencyId = :agencyId')

                        ->addOrderBy('c.id', 'ASC')
                        ->andWhere('c.article = :articl')
                        ->setParameter('articl', $article);
                },
            ))
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

        $resolver->setRequired([
            'article',
        ]);
    }
}
