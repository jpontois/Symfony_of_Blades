<?php

namespace App\Repository;

use App\Entity\GameEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GameEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameEntity[]    findAll()
 * @method GameEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameEntity::class);
    }

    // /**
    //  * @return GameEntity[] Returns an array of GameEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameEntity
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
