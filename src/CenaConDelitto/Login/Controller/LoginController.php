<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Controller;

use CenaConDelitto\Login\Dto\GuestAccessRequest;
use CenaConDelitto\Login\UseCase\GuestAccessUseCase;
use CenaConDelitto\Shared\Entity\User;
use CenaConDelitto\Shared\Enum\UserRoles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);

    }

    #[Route('/login/success', name: 'login_success')]
    public function loginSuccess(#[CurrentUser] User $user): Response
    {
        $redirectRoute = 'home';
        if (in_array(UserRoles::Admin->value, $user->getRoles())) {
            $redirectRoute = 'admin_home';
        }

        return $this->redirectToRoute($redirectRoute);
    }

    #[Route('/guest-access', name: 'guest_access', methods: ['POST'])]
    public function guestAccess(GuestAccessRequest $guestAccessRequest, GuestAccessUseCase $useCase, Security $security): Response
    {
        if ($security->getUser()) {
            return $this->errorResponse('You are already logged');
        }

        try {
            $user = $useCase->execute($guestAccessRequest);
        } catch (AccessDeniedException $e) {
            return $this->errorResponse($e->getMessage());
        }

        $security->login($user);

        return $this->json(['uuid' => $user->getUuid()]);
    }

    private function errorResponse(string $error, int $status = 500): Response
    {
        return $this->json(['error' => $error], $status);
    }
}
