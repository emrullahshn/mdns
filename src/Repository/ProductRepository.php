<?php

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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

    public function fetchAllByIds(array $productIds)
    {
        return $this->createQueryBuilder('p')
            ->where('p.id IN (:productIds)')
            ->setParameter('productIds', $productIds)
            ->getQuery()
            ->getResult();
    }

    public function getByBrandAndCategory(Campaign $campaign)
    {
        $qb = $this->createQueryBuilder('p');
        if ($campaign->getBrand() !== null) {
            $qb
                ->andWhere('p.brand = :brand')
                ->setParameter('brand', $campaign->getBrand());
        }

        if ($campaign->getCategory() !== null) {
            $qb
                ->andWhere('p.category = :category')
                ->setParameter('category', $campaign->getCategory());
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}
