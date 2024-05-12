<?php

namespace App\Repository;

use App\Entity\AnimalHabitatImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalHabitatImage>
 *
 * @method AnimalHabitatImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalHabitatImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalHabitatImage[]    findAll()
 * @method AnimalHabitatImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalHabitatImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalHabitatImage::class);
    }

//    /**
//     * @return AnimalHabitatImage[] Returns an array of AnimalHabitatImage objects
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

//    public function findOneBySomeField($value): ?AnimalHabitatImage
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
