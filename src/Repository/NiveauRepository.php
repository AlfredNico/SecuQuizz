<?php

namespace App\Repository;

use App\Entity\Niveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Niveau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Niveau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Niveau[]    findAll()
 * @method Niveau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Niveau::class);
    }

    // /**
    //  * @return Niveau[] Returns an array of Niveau objects
    //  */
    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('n')
    //         ->andWhere('n.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('n.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // /**
    //  * @return Niveau[] Returns an array of Niveau objects
    //  */
    // public function findAll()
    // {
    //     $query = $this->createQueryBuilder('s');
    //     $query->select('s');
    //     // $query->where('s.id = :min')->setParameter('min', 'min_id');
    //     $query->groupBy('s.id');
    //     // $query->setMaxResults($limit);
    //     $query->orderBy('s.id', 'DESC');
    //     $query->setMaxResults(1);

    //     return $query->getQuery()->getOneOrNullResult();
    // }


    // public function findOneIdField(): ?Niveau
    // {
    //     $query = $this->createQueryBuilder('s');
    //     $query->select('s.id, MIN(s.id) AS min_id');
    //     // $query->where('s.challenge = :challenge')->setParameter('challenge', $value);
    //     $query->groupBy('s.id');
    //     // $query->setMaxResults($limit);
    //     $query->orderBy('s.id', 'DESC');
    //     $query->setMaxResults(1);

    //     return $query->getQuery()->getOneOrNullResult();
    // }
}
