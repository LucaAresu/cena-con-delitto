<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Controller;

use CenaConDelitto\Login\Dto\GuestAccessRequest;
use CenaConDelitto\Login\UseCase\GuestAccessUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: 'GET')]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }

    #[Route('/guest-access', name: 'guest_access', methods: ['GET', 'POST'])]
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
