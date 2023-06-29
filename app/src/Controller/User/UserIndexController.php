<?php

namespace App\Controller\User;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserIndexController extends AbstractController
{

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(name: 'user_index')]
    public function index () : Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
