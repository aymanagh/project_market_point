<?php

namespace App\Repository;

use App\Entity\ProductionLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductionLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionLine[]    findAll()
 * @method ProductionLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductionLine::class);
    }

    /**
     * Afficher toutes les commandes par atelier
     *
     * @return void
     */
    public function findAllProductionLineByWorkshop(Object $id)
    {
        $qb = $this->createQueryBuilder('o')
            ->setParameter('workshop', $id)
            ->where('o.workshop = :workshop');

        $query = $qb->getQuery();

        return $query->execute();
    }

    // /**
    //  * @return ProductionLine[] Returns an array of ProductionLine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductionLine
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
