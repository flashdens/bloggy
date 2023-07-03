<?php

namespace App\Service;

use App\Entity\User;

/**
 * Interface UserServiceInterface.
 *
 * This interface defines methods for managing users.
 */
interface UserServiceInterface
{
    /**
     * Save a user.
     *
     * @param User $user The user to save
     */
    public function saveUser(User $user): void;

    /**
     * Delete a user.
     *
     * @param User $user The user to delete
     */
    public function deleteUser(User $user): void;
}
