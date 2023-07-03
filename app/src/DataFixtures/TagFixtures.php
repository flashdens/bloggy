<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * TagFixtures class.
 */
class TagFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * Get the dependencies of the TagFixtures.
     *
     * @return array The dependencies
     */
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    /**
     * Load data for Tag fixtures.
     */
    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }
        $this->createMany(10, 'tags', function () {
            $tag = new Tag();
            $tag->setTitle($this->faker->word());
            $tag->setSlug($this->faker->sentence(2));
            $date = $this->faker->dateTimeBetween('-100 days', '-1 days');
            $tag->setUpdatedAt($date);
            $tag->setCreatedAt($date);

            return $tag;
        });

        $this->manager->flush();
    }
}
