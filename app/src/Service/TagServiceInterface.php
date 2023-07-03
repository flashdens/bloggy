<?php

namespace App\Service;

use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface.
 *
 * This interface defines methods for managing tags.
 */
interface TagServiceInterface
{
    /**
     * Find a tag by its title.
     *
     * @param string $title The title of the tag
     *
     * @return Tag|null The tag entity if found, null otherwise
     */
    public function findOneByTitle(string $title): ?Tag;

    /**
     * Save a tag.
     *
     * @param Tag $tag The tag to save
     */
    public function saveTag(Tag $tag): void;

    /**
     * Delete a tag.
     *
     * @param Tag $tag The tag to delete
     */
    public function deleteTag(Tag $tag): void;

    /**
     * Get a paginated list of all tags.
     *
     * @param int $page The page number
     *
     * @return PaginationInterface The paginated list of tags
     */
    public function getPaginatedListOfAllTags(int $page): PaginationInterface;

    /**
     * Get a paginated list of posts associated with a specific tag.
     *
     * @param int $page The page number
     * @param Tag $tag  The tag entity
     *
     * @return PaginationInterface The paginated list of posts
     */
    public function getPaginatedListOfPostsByTag(int $page, Tag $tag): PaginationInterface;

    /**
     * Check if a tag is already included in an array.
     *
     * @param Tag   $tag  Tag entity to check
     * @param Tag[] $tags Array of tags
     *
     * @return bool True if the tag is included, false otherwise
     */
    public function includesTag(Tag $tag, array $tags): bool;

    /**
     * Find a tag by its ID.
     *
     * @param int $id The ID of the tag
     *
     * @return Tag|null The tag entity if found, null otherwise
     */
    public function findOneById(int $id): ?Tag;
}
