<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
#[ORM\Table(name: 'avatars')]

/**
 * Avatar class.
 */
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'avatar', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 191)]
    private ?string $fileName = null;

    /**
     * Get the ID of the avatar.
     *
     * @return int|null The ID
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the user associated with the avatar.
     *
     * @return User|null The user
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the user associated with the avatar.
     *
     * @param User $user The user
     *
     * @return $this
     */
    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the file name of the avatar.
     *
     * @return string|null The file name
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * Set the file name of the avatar.
     *
     * @param string $fileName The file name
     *
     * @return $this
     */
    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }
}
