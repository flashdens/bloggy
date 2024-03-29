<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * PostFixtures class.
 */
class PostFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Constructor for PostFixtures.
     */
    public function __construct()
    {
    }

    /**
     * Get the dependencies of the PostFixtures.
     *
     * @return array The dependencies
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class];
    }

    /**
     * Load data for Post fixtures.
     */
    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }
        $this->createMany(20, 'posts', function () {
            $post = new Post();
            $post->setTitle($this->faker->sentence(4));
            $post->setContent($this->faker->text(1500));
            for ($i = 0; $i < rand(0, 3); ++$i) {
                $tag = $this->getRandomReference('tags');
                $post->addTag($tag);
            }
            $category = $this->getRandomReference('categories');
            $post->setCategory($category);
            $date = $this->faker->dateTimeBetween('-100 days', '-1 days');
            $post->setEdited($date);
            $post->setPublished($date);
            $post->setImage('default.jpeg');

            return $post;
        });
        $this->manager->flush();
    }
}
