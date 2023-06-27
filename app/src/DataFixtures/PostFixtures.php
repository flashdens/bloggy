<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function __construct () {

    }

    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }
        $this->createMany(20, 'posts', function () {
            $post = new Post();
            $post->setTitle($this->faker->sentence(4));
            $post->setContent($this->faker->text(300));
            $tag = $this->getRandomReference('tags');
            $post->addTag($tag);
            $category = $this->getRandomReference('categories');
            $post->setCategory($category);
            $date = $this->faker->dateTimeBetween('-100 days', '-1 days');
            $post->setEdited($date);
            $post->setPublished($date);
            return $post;
        }); {
        $this->manager->flush();
    }
    }
    public function getDependencies(): array
    {
        return [CategoryFixtures::class, TagFixtures::class];
    }
}