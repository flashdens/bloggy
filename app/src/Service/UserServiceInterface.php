<?php

namespace App\Service;

use App\Entity\User;

interface UserServiceInterface
{
    public function saveUser(User $user): void;

    public function deleteUser(User $user): void;
}
