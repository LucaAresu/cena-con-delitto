<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Controller;

use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Enum\UserRoles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, #[CurrentUser] null|User $user): Response
    {
        if (null !== $user) {
            return $this->redirectToRoute('handle_user_logged');
        }

        return $this->render('login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/', name: 'handle_user_logged')]
    public function handleUserLogged(#[CurrentUser] null|User $user): Response
    {
        if (null === $user) {
            return $this->redirectToRoute('app_login');
        }

        $redirectRoute = 'home';
        if (in_array(UserRoles::Admin->value, $user->getRoles(), true)) {
            $redirectRoute = 'admin_home';
        }

        return $this->redirectToRoute($redirectRoute);
    }
}
