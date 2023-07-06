<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * PostRepository constructor.
     *
     * @param ManagerRegistry $registry The manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * Saves the Post entity.
     *
     * @param Post $entity The post entity
     */
    public function save(Post $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes the Post entity.
     *
     * @param Post $entity The post entity
     */
    public function remove(Post $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds posts by a specific tag.
     *
     * @param Tag $tag The tag entity
     *
     * @return Post[] The array of posts
     */
    public function findPostsByTag(Tag $tag): array
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.tags', 'tag')
            ->where('tag.id = :tagId')
            ->setParameter('tagId', $tag->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds a single post entity by its title.
     *
     * @return Post|null The post entity or null if it does not exist
     *
     * @throws NonUniqueResultException
     */

    /**
     * Returns a query builder for retrieving all posts with optional filters.
     *
     * @param array $filters The filters to apply (category, tag, post)
     *
     * @return QueryBuilder The query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial post.{id, published, edited, title, content, views, image}',
                'partial category.{id, name}',
                'partial tags.{id, title}'
            )
            ->join('post.category', 'category')
            ->leftJoin('post.tags', 'tags')
            ->orderBy('post.edited', 'DESC');

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Applies filters to the query builder.
     *
     * @param QueryBuilder $queryBuilder The query builder
     * @param array        $filters      The filters to apply
     *
     * @return QueryBuilder The updated query builder
     */
    public function applyFiltersToList(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (isset($filters['category']) && $filters['category'] instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters['category']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }

        if (isset($filters['post'])) {
            $queryBuilder
                ->andWhere('post.title LIKE :title')
                ->setParameter(
                    'title',
                    '%'.$filters['post']['search'].'%'
                );
        }

        return $queryBuilder;
    }

    /**
     * Deletes a post entity.
     *
     * @param Post $post The post entity
     */
    public function delete(Post $post): void
    {
        $this->_em->remove($post);
        $this->_em->flush();
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
        return $queryBuilder ?? $this->createQueryBuilder('post');
    }
}
