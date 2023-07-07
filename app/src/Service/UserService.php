<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * User service.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Comment repository.
     */
    private CommentRepository $commentRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * UserService constructor.
     *
     * @param UserRepository     $userRepository    The user repository
     * @param CommentRepository  $commentRepository The comment repository
     * @param PaginatorInterface $paginator         The paginator
     */
    public function __construct(UserRepository $userRepository, CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    /**
     * Save a user.
     *
     * @param User $user User entity to save
     */
    public function saveUser(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Delete a user.
     *
     * @param User $user User entity to delete
     */
    public function deleteUser(User $user): void
    {
        $this->userRepository->remove($user);
    }

    /**
     * Ban or unban a user.
     *
     * @param User $user User entity to ban or unban
     */
    public function banOrUnbanUser(User $user): void
    {
        ($user->isBanned()) ?
            $user->setIsBanned(false) :
            $user->setIsBanned(true);
        $this->userRepository->save($user);
    }

    /**
     * Get a paginated list of users.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface Paginated list of users
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get a paginated list of comments by user.
     *
     * @param int  $page Page number
     * @param User $user User entity
     *
     * @return PaginationInterface Paginated list of comments
     */
    public function getPaginatedListOfComments(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->findBy(
                ['author' => $user]
            ),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
}
