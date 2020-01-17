<?php

namespace App\Repository;

use App\Entity\EditorEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EditorEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method EditorEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method EditorEntity[]    findAll()
 * @method EditorEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditorEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EditorEntity::class);
    }

    // /**
    //  * @return EditorEntity[] Returns an array of EditorEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EditorEntity
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
