<?php

namespace App\Repository;

use App\Entity\MarketOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MarketOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method MarketOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method MarketOrder[]    findAll()
 * @method MarketOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MarketOrder::class);
    }

    /**
     * Afficher toutes les commandes par atelier
     *
     * @return void
     */
    public function findAllOrderByWorkshop(Object $id)
    {
        $qb = $this->createQueryBuilder('o')
            ->setParameter('workshop', $id)
            ->where('o.workshop = :workshop');

        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * Afficher toutes les commandes par utilisateur
     *
     * @return void
     */
    public function findAllOrderByUser(int $id)
    {
        $qb = $this->createQueryBuilder('o')
            ->setParameter('user', $id)
            ->where('o.user = :user');

        $query = $qb->getQuery();

        return $query->execute();
    }

    // /**
    //  * @return MarketOrder[] Returns an array of MarketOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MarketOrder
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
