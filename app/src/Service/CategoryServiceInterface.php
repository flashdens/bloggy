<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Tag;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CategoryServiceInterface
{
    public function getPaginatedList(int $page): PaginationInterface;

    public function findOneById(int $id): ?Category;
}
