<?php

namespace App\Repository;

use App\Entity\CCmovimientos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CCmovimientos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CCmovimientos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CCmovimientos[]    findAll()
 * @method CCmovimientos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CCmovimientosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CCmovimientos::class);
    }

    // /**
    //  * @return CCmovimientos[] Returns an array of CCmovimientos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CCmovimientos
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
