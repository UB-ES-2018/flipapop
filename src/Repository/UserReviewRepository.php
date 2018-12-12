<?php

namespace App\Repository;

use App\Entity\UserReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserReview[]    findAll()
 * @method UserReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserReviewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserReview::class);
    }

    // /**
    //  * @return UserReview[] Returns an array of UserReview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserReview
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
