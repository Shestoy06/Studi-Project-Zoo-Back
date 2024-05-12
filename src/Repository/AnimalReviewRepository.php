<?php

namespace App\Repository;

use App\Entity\AnimalReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalReview>
 *
 * @method AnimalReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalReview[]    findAll()
 * @method AnimalReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalReview::class);
    }

//    /**
//     * @return AnimalReview[] Returns an array of AnimalReview objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnimalReview
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
