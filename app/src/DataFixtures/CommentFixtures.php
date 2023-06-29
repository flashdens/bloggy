<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function __construct()
    {
    }

    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }
        $this->createMany(10, 'comments', function () {
            $comment = new Comment();
            $comment->setContent($this->faker->unique()->sentence);
            $user = $this->getRandomReference('users');
            $comment->setAuthor($user);
            $post = $this->getRandomReference('posts');
            $comment->setPost($post);
            $comment->setPublished(\DateTimeImmutable::createFromMutable(
                $this->faker->dateTimeBetween('-100 days', '-1 days')
            ));

            return $comment;
        });
        $this->manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class, PostFixtures::class];
    }
}
