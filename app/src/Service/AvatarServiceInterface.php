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
 * Interface AvatarServiceInterface.
 */
interface AvatarServiceInterface
{
    /**
     * Create an avatar.
     *
     * @param UploadedFile  $uploadedFile Uploaded file
     * @param Avatar        $avatar       Avatar entity
     * @param UserInterface $user         User interface
     */
    public function create(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void;

    /**
     * Update an avatar.
     *
     * @param UploadedFile  $uploadedFile Uploaded file
     * @param Avatar        $avatar       Avatar entity
     * @param UserInterface $user         User interface
     */
    public function update(UploadedFile $uploadedFile, Avatar $avatar, UserInterface $user): void;

    /**
     * Delete an avatar.
     *
     * @param Avatar        $avatar Avatar entity
     * @param UserInterface $user   User interface
     */
    public function delete(Avatar $avatar, UserInterface $user): void;
}
