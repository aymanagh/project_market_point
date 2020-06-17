<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Requête pour rechercher les produits
     *
     * @param Object $id
     * @param ProductSearch $search
     * @return Query
     */
    public function findAllProductSearch(ProductSearch $search): Query
    {
        $query = $this->findAllProduct();

        if ($search->getReference()) {
            $query = $query
                ->andWhere('p.reference = :reference')
                ->setParameter('reference', $search->getReference());
        }

        if ($search->getProductCategorie()) {
            $query = $query
                ->andWhere('p.productCategorie = :productCategorie')
                ->setParameter('productCategorie', $search->getProductCategorie());
        }

        if ($search->getLiness()) {
            $query = $query
                ->leftJoin('p.liness', 't')
                ->addSelect('t');
            //->andWhere('p.liness = :liness')
            //->setParameter('liness', $search->getLiness());
        }

        if ($search->getOps()) {
            $query = $query
                ->andWhere('p.ops = :ops')
                ->setParameter('ops', $search->getOps());
        }

        return $query->getQuery();
    }

    /**
     * Requête pour afficher les produits
     *
     * @param Object $id
     * @return QueryBuilder
     */
    private function findAllProduct(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.reference = false');
    }

    /**
     * Requête pour rechercher les produits par atelier
     *
     * @param Object $id
     * @param ProductSearch $search
     * @return Query
     */
    public function findAllProductByWorkshopSearch(Object $id, ProductSearch $search): Query
    {
        $query = $this->findAllProductByWorkshop($id);

        if ($search->getReference()) {
            $query = $query
                ->andWhere('p.reference = :reference')
                ->setParameter('reference', $search->getReference());
        }

        if ($search->getProductCategorie()) {
            $query = $query
                ->andWhere('p.productCategorie = :productCategorie')
                ->setParameter('productCategorie', $search->getProductCategorie());
        }

        if ($search->getLiness()) {
            $query = $query
                ->andWhere('p.liness = :liness')
                ->setParameter('liness', $search->getLiness());
        }

        if ($search->getOps()) {
            $query = $query
                ->andWhere('p.ops = :ops')
                ->setParameter('ops', $search->getOps());
        }

        return $query->getQuery();
    }

    /**
     * Requête pour afficher les produits par atelier
     *
     * @param Object $id
     * @return QueryBuilder
     */
    private function findAllProductByWorkshop(Object $id): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.reference = false')
            ->setParameter('workshop', $id)
            ->andWhere('p.workshop = :workshop');
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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
    public function findOneBySomeField($value): ?Product
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
