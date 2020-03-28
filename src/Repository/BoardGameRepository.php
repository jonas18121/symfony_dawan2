<?php

namespace App\Repository;

use App\Entity\BoardGame;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method BoardGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoardGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoardGame[]    findAll()
 * @method BoardGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoardGame::class);
    }

    /**
     * @return BoardGame[]
     */
    public function findWithCategories()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.categories', 'c')
            ->addSelect('c')
            ->orderBy('b.releasedAt', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return BoardGame[]
     */
    public function findWithCategoriesBis()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT b, c FROM '.BoardGame::class.' b '.
            'LEFT JOIN b.categories c '.
            'ORDER BY b.releasedAt DESC'
        )->setMaxResults(50)
        ->getResult();
    }

    public function findByClassifiedInOne(Category $category)
    {
        return $this->createQueryBuilder('b')
            ->join('b.categories', 'c', Join::WITH, 'c = :category')
            ->orderBy('b.releasedAt', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->setParameter('category', $category)
            ->getResult()
        ;
    }


    // /**
    //  * @return BoardGame[] Returns an array of BoardGame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BoardGame
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
