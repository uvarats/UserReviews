<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null search($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @param Review $entity
     * @param bool $flush
     */
    public function add(Review $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Review $entity
     * @param bool $flush
     */
    public function remove(Review $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getNewReviews(int $count)
    {
        $em = $this->getEntityManager();
        return $em->createQuery(
            "SELECT r
             FROM App\Entity\Review r
             ORDER BY r.creationTime DESC
             "
        )
            ->setMaxResults($count)
            ->getResult();
    }
    public function getTopReviews(int $count)
    {
        $em = $this->getEntityManager();
        return $em->createQuery(
            "SELECT r
            FROM App\Entity\Review r
            ORDER BY r.likes DESC
            "
        )
            ->setMaxResults($count)
            ->getResult();
    }
    public function getReviewsByTag(string $tag): array
    {
        $em = $this->getEntityManager();
        /** @var Review[] $reviews */
        $reviews = $em->createQuery(
            "SELECT r, t
            FROM App\Entity\Review r
            JOIN r.tags t"
        )->getResult();
        return array_filter($reviews, function ($value, $key) use ($tag) {
            return in_array($tag, $value->getTags()->toArray());
        }, ARRAY_FILTER_USE_BOTH);
    }
    public function customFind(int $id) : ?Review
    {
        $em = $this->getEntityManager();
        return $em->createQuery(
            'SELECT r, t, c
             FROM App\Entity\Review r
             JOIN r.tags t
             JOIN r.comments c
             WHERE r.id = :id'
        )->setParameter('id', $id)
            ->getResult()[0];
    }
//    public function removeReviewComments(int $id){
//        $em = $this->getEntityManager();
//
//    }
    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Review
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
