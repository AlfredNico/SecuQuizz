<?php

namespace App\Repository;

use App\Entity\Families;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Families|null find($id, $lockMode = null, $lockVersion = null)
 * @method Families|null findOneBy(array $criteria, array $orderBy = null)
 * @method Families[]    findAll()
 * @method Families[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamiliesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Families::class);
    }

    /**
     * @return Families[] Returns an array of Families objects
     */

    public function findById($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Families
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
