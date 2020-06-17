<?php

namespace App\Repository;

use App\Entity\Op;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Op|null find($id, $lockMode = null, $lockVersion = null)
 * @method Op|null findOneBy(array $criteria, array $orderBy = null)
 * @method Op[]    findAll()
 * @method Op[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Op::class);
    }

    // /**
    //  * @return Op[] Returns an array of Op objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Op
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
