<?php

namespace App\Repository;

use App\Entity\ClientRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientRate>
 *
 * @method ClientRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientRate[]    findAll()
 * @method ClientRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientRate::class);
    }

//    /**
//     * @return ClientRate[] Returns an array of ClientRate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ClientRate
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
