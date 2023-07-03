<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * Gets or creates a QueryBuilder instance.
     *
     * @param QueryBuilder|null $queryBuilder The query builder (optional)
     *
     * @return QueryBuilder The query builder instance
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('c');
    }

    /**
     * CategoryRepository constructor.
     *
     * @param ManagerRegistry $registry The registry for managing entities.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }


    /**
     * Returns a query builder for retrieving all categories.
     *
     * @return QueryBuilder The QueryBuilder instance.
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('partial c.{id, name}')
            ->join()
            ->orderBy('c.id', 'ASC');
    }

    /**
     * Saves the Category entity.
     *
     * @param Category $entity The Category entity to save.
     *
     * @return void
     */
    public function save(Category $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes the Category entity.
     *
     * @param Category $entity The Category entity to remove.
     *
     * @return void
     */
    public function remove(Category $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    // # Uncomment and update the method if needed

    // /**
    //  * Finds Category objects by example field.
    //  *
    //  * @param mixed $value The example value.
    //  *
    //  * @return Category[] The found Category objects.
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('c.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    // /**
    //  * Finds a single Category object by some field.
    //  *
    //  * @param mixed $value The field value.
    //  *
    //  * @return Category|null The found Category object or null if not found.
    //  */
    // public function findOneBySomeField($value): ?Category
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
