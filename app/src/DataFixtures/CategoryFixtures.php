<?php

namespace App\DataFixtures;

use App\Entity\Category;

/**
 * CategoryFixtures class.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data for Category fixtures.
     */
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
