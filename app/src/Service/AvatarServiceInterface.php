<?php
/**
 * Avatar service interface.
 */

namespace App\Service;

use App\Entity\Avatar;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Avatar service.
 */
interface AvatarServiceInterface
{
    /**
     * Create avatar.
     *
     * @param UploadedFile $uploadedFile Uploaded file
     * @param Avatar $avatar Avatar entity
     * @param User $user User interface
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void;

    public function update(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void;

    public function delete(Avatar $avatar, UserInterface $user): void;

}
