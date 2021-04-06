<?php

namespace App\Repository;

use App\Entity\Hashtags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hashtags|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hashtags|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hashtags[]    findAll()
 * @method Hashtags[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashtagsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hashtags::class);
    }

    // /**
    //  * @return Hashtags[] Returns an array of Hashtags objects
    //  */

    public function findByDate($value)
    {
        return $this->createQueryBuilder('h')
            ->leftJoin('h.news', 'news')
            ->where('news.publicatDate > :val')
            ->setParameter('val', $value)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Hashtags
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
