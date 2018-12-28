<?php

namespace App\Repository;

use App\Entity\MovDiaria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MovDiaria|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovDiaria|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovDiaria[]    findAll()
 * @method MovDiaria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovDiariaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MovDiaria::class);
    }

    // /**
    //  * @return MovDiaria[] Returns an array of MovDiaria objects
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
    public function findOneBySomeField($value): ?MovDiaria
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