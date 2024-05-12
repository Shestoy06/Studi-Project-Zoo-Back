<?php

namespace App\Repository;

use App\Entity\AnimalHabitat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnimalHabitat>
 *
 * @method AnimalHabitat|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnimalHabitat|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnimalHabitat[]    findAll()
 * @method AnimalHabitat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalHabitatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimalHabitat::class);
    }

//    /**
//     * @return AnimalHabitat[] Returns an array of AnimalHabitat objects
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

//    public function findOneBySomeField($value): ?AnimalHabitat
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
