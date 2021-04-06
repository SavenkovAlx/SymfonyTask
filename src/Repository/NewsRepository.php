<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    public function findAllQuery()
    {
        return $this->findBy(array(), array('publicatDate' => 'DESC'));
    }

    // /**
    //  * @return News[] Returns an array of News objects
    //  */
    public function findByTag($value)
    {
        return $this->createQueryBuilder('news')
            ->join('news.hashtag', 'tag')
            ->where('tag.tag LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('news.publicatDate')
            ->getQuery();
    }

    public function searchByQuery(string $query, string $where)
    {
        if ($where == 'hashtag') {
            return $this->findByTag($query);
        }
        return $this->createQueryBuilder('n')
            ->where("n.$where LIKE :query")
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('n.publicatDate')
            ->getQuery();
    }


    /*
    public function findOneBySomeField($value): ?News
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}