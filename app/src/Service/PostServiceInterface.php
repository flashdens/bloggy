<?php

namespace App\Service;

use App\Entity\Post;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface PostServiceInterface.
 */
interface PostServiceInterface
{
    /**
     * Get a paginated list of posts.
     *
     * @param int   $page    Page number
     * @param array $filters Filters to apply
     *
     * @return PaginationInterface Paginated list of posts
     */
    public function getPaginatedList(int $page, array $filters = []): PaginationInterface;

    /**
     * Increment the views of a post.
     *
     * @param Post $post Post entity
     */
    public function incrementViews(Post $post): void;

    /**
     * Save a post.
     *
     * @param Post              $post  Post entity
     * @param UploadedFile|null $image Uploaded image file
     */
    public function savePost(Post $post, ?UploadedFile $image): void;

    /**
     * Delete a post.
     *
     * @param Post $post Post entity
     */
    public function deletePost(Post $post): void;

    /**
     * Delete comments of a post.
     *
     * @param Post $post Post entity
     */
    public function deleteComments(Post $post): void;

    /**
     * Find a post by its title.
     *
     * @param string $title Post title
     *
     * @return Post|null Found post or null if not found
     */
    public function findOneByTitle(string $title): ?Post;

    /**
     * Prepare filters for querying posts.
     *
     * @param array $filters Filters to apply
     *
     * @return array Prepared filters
     */
    public function prepareFilters(array $filters): array;
}
