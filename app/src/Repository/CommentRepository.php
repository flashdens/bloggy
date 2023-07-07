<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * CommentRepository constructor.
     *
     * @param ManagerRegistry $registry The manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * Saves the Comment entity.
     *
     * @param Comment $comment The comment entity
     */
    public function save(Comment $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush();
    }

    /**
     * Removes the Comment entity.
     *
     * @param Comment $entity The comment entity
     */
    public function remove(Comment $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Find comments by post.
     *
     * @return array comments
     */

    /**
     * Find all comments.
     *
     * @return array comments
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('comment')
            ->select('partial comment. {id, content},
                partial post.{id}')
            ->join('comment.post', 'post');
    }

    /**
     * Gets or creates a QueryBuilder instance.
     *
     * @param QueryBuilder|null $queryBuilder The query builder (optional)
     *
     * @return QueryBuilder The query builder instance
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('comment');
    }

    // # Uncomment and update the method if needed

    // /**
    //  * Finds Comment objects by example field.
    //  *
    //  * @param mixed $value
    //  *
    //  * @return Comment[]
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
    //  * Finds a single Comment object by some field.
    //  *
    //  * @param mixed $value
    //  *
    //  * @return Comment|null
    //  */
    // public function findOneBySomeField($value): ?Comment
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}
