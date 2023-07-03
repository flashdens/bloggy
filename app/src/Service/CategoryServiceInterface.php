<?php

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    /**
     * Get a paginated list of categories.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list of categories
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Find a category by ID.
     *
     * @param int $id Category ID
     *
     * @return Category|null Category entity or null if not found
     */
    public function findOneById(int $id): ?Category;
}
