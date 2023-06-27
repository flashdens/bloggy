<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class UserService implements UserServiceInterface
{

    private UserRepository $userRepository;

    private CommentRepository $commentRepository;

    private PaginatorInterface $paginator;

    public function __construct(UserRepository $userRepository, CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    public function saveUser(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function deleteUser(User $user) : void
    {
        $this->userRepository->remove($user);
    }

    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

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