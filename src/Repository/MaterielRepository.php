<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materiel>
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

   public function findAvailableProducts($quantity): array
   {
       return $this->createQueryBuilder('m')
           ->andWhere('m.quantite > :quantity')
           ->setParameter('quantity', $quantity)
           ->getQuery()
           ->getResult()
       ;
   }

}
