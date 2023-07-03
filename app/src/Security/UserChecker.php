<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserChecker
 */
class UserChecker implements UserCheckerInterface
{
    /**
     * Checks the user account status before authentication.
     *
     * @param UserInterface $user The user object
     *
     * @throws CustomUserMessageAccountStatusException If the user is banned
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->isBanned()) {
            // The message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('message.user_banned');
        }
    }

    /**
     * Checks the user account status after authentication.
     *
     * @param UserInterface $user The user object
     */
    public function checkPostAuth(UserInterface $user): void
    {
        // No additional checks needed after authentication
    }
}
