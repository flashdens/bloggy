<?php

/*
 *
 TODO:
 - Argument #1 ($object) must be of type ?object, array given
 - registration
/*


/**
 * Tags data transformer.
 */

namespace App\Form\Type\DataTransformer;

use App\Entity\Tag;
use App\Service\TagServiceInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 *
 * @implements DataTransformerInterface<mixed, mixed>
 */
class DataTransformer implements DataTransformerInterface
{
    /**
     * Tag service.
     */
    private TagServiceInterface $tagService;
    private EntityManagerInterface $entityManager;

    /**
     * Constructor.
     *
     * @param TagServiceInterface $tagService Tag service
     */
    public function __construct(EntityManagerInterface $entityManager, TagServiceInterface $tagService)
    {
        $this->entityManager = $entityManager;
        $this->tagService = $tagService;
    }

    /**
     * Transform array of tags to string of tag titles.
     *
     * @param Collection<int, Tag> $value Tags entity collection
     *
     * @return array|object[]|Tag[] Result
     */
    public function transform($value): array
    {

        $tagTitles = [];

        if ($value->isEmpty()) {
            return $tagTitles;
        }


        return $this->entityManager
        ->getRepository(Tag::class)
        ->findBy(["title" => $value]);
    }

    /**
     * Transform string of tag names into array of Tag entities.
     *
     * @param string $value String of tag names
     *
     * @return array<int, Tag> Result
     */
    public function reverseTransform($value): array
    {
        $tagTitles = explode(',', $value);

        $tags = [];

        foreach ($tagTitles as $tagTitle) {
            if ('' !== trim($tagTitle)) {
                $tag = $this->tagService->findOneByTitle(strtolower($tagTitle));
                if (null === $tag) {
                    $tag = new Tag();
                    $tag->setTitle($tagTitle);

                    $this->tagService->save($tag);
                }
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}
