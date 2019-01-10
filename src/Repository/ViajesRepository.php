<?php

namespace App\Repository;

use App\Entity\Viajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Viajes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Viajes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Viajes[]    findAll()
 * @method Viajes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViajesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Viajes::class);
    }

     /**
      * @return Viajes[] Returns an array of Viajes objects
      */

    public function findByDeuda($idChofer)
    {
        return $this->createQueryBuilder('v')
            -> select("sum(v.monto)")
            -> andWhere('v.chofer = :chofi and v.estado = :estado')
            -> setParameter('chofi', $idChofer)
            -> setParameter('estado', '0')
            -> orderBy('v.id', 'ASC')
            -> getQuery()
            -> getResult()
        ;
    }


    public function findByRec($idChofer,$od)
    {
        return $this->createQueryBuilder('v')
            -> select("sum(v.monto)")
            -> andWhere('v.chofer = :chofi and v.oDiaria = :od')
            -> setParameter('chofi', $idChofer)
            -> setParameter('od', $od)
            -> orderBy('v.id', 'ASC')
            -> getQuery()
            -> getResult()
        ;
    }


    public function findByCierre($idChofer)
    {
        return $this->createQueryBuilder('v')
            -> andWhere('v.chofer = :chofi and v.estado = :estado')
            -> setParameter('chofi', $idChofer)
            -> setParameter('estado', '0')
            -> orderBy('v.id', 'ASC')
            -> getQuery()
            -> getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Viajes
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
