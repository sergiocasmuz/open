<?php

namespace App\Repository;

use App\Entity\Cuentas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cuentas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cuentas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cuentas[]    findAll()
 * @method Cuentas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CuentasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cuentas::class);
    }

     /**
      * @return Cuentas[] Returns an array of Cuentas objects
      */

    public function findByIngresos($od)
    {
        return $this->createQueryBuilder('c')
            ->andWhere("c.oDiaria = :val and c.monto > 0 ")
            ->setParameter('val', $od)
            ->select('SUM(c.monto) as suma')
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }



    public function findByDeudas($od)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.oDiaria = :val')
            ->setParameter('val', $od)
            ->select('SUM(c.monto) as suma')
            ->getQuery()
            ->getOneOrNullResult();
        ;
    }





}
