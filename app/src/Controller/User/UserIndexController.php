<?php

namespace App\Controller\User;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserIndexController.
 */
#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserIndexController extends AbstractController
{
    /**
     * Security.
     */
    private Security $security;

    /**
     * UserIndexController constructor.
     *
     * @param Security $security Security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route(name: 'user_index')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
