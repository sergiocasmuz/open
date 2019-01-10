<?php

namespace App\Repository;

use App\Entity\OrdenDiaria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrdenDiaria|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrdenDiaria|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrdenDiaria[]    findAll()
 * @method OrdenDiaria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdenDiariaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrdenDiaria::class);
    }

     /**
      * @return OrdenDiaria[] Returns an array of OrdenDiaria objects
      */

    public function findByOD()
    {
        return $this->createQueryBuilder('o')
            ->select('o, MAX(o.id)')
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?OrdenDiaria
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
