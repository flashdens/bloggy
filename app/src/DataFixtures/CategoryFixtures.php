<?php

namespace App\DataFixtures;

use App\Entity\Category;

class CategoryFixtures extends AbstractBaseFixtures
{
    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }
        $this->createMany(10, 'categories', function () {
            $category = new Category();
            $category->setName($this->faker->word());

            return $category;
        });
        $this->manager->flush();
    }
}
