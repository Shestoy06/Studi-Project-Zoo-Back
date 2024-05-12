<?php

namespace App\Repository;

use App\Entity\AnimalImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalImage>
 *
 * @method AnimalImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalImage[]    findAll()
 * @method AnimalImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalImage::class);
    }

//    /**
//     * @return AnimalImage[] Returns an array of AnimalImage objects
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

//    public function findOneBySomeField($value): ?AnimalImage
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
