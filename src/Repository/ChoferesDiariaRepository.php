<?php

namespace App\Repository;

use App\Entity\ChoferesDiaria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChoferesDiaria|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChoferesDiaria|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChoferesDiaria[]    findAll()
 * @method ChoferesDiaria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChoferesDiariaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChoferesDiaria::class);
    }

     /**
      * @return ChoferesDiaria[] Returns an array of ChoferesDiaria objects
      */

    public function findByOrdenDiaria($idChofer,$od)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.choferId = :idChofer and c.oDiaria= :od and c.estado = :estado')
            ->setParameter('idChofer', $idChofer)
            ->setParameter('od', $od)
            ->setParameter('estado', 0)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByCheck($idChofer, $od)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.choferId = :idChofer and c.oDiaria= :od and c.estado = :estado')
            ->setParameter('idChofer', $idChofer)
            ->setParameter('od', $od)
            ->setParameter('estado', "0")
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?ChoferesDiaria
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
