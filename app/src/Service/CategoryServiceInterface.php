<?php

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CategoryServiceInterface
{
    public function getPaginatedList (int $page) : PaginationInterface;
    public function findOneById(int $id): ?Category;

}