<?php

namespace App\Repository;

use App\Entity\Questions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Questions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Questions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Questions[]    findAll()
 * @method Questions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questions::class);
    }

    /**
     * @return Questions[] Returns an array of Questions objects
     */

    public function findById($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.article = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Questions[] Returns an array of Questions objects
     */

    public function findByIdUser($value, $value1)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.article = :val')
            ->andWhere('q.users = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', $value1)
            ->orderBy('q.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Questions
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
