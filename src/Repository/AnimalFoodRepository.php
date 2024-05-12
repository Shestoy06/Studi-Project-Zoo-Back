<?php

namespace App\Repository;

use App\Entity\AnimalFood;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalFood>
 *
 * @method AnimalFood|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalFood|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalFood[]    findAll()
 * @method AnimalFood[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalFoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalFood::class);
    }

//    /**
//     * @return AnimalFood[] Returns an array of AnimalFood objects
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

//    public function findOneBySomeField($value): ?AnimalFood
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
