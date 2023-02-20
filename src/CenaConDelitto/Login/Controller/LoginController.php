<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Controller;

use CenaConDelitto\Login\UseCase\GuestAccessUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: 'GET')]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }

    #[Route('/guest-access', name: 'guest_access', methods: ['GET', 'POST'])]
    public function guestAccess(Request $request, GuestAccessUseCase $useCase, Security $security): Response
    {
        try {
            $user = $useCase->execute($request);
        } catch (AccessDeniedException $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }

        $security->login($user);

        return $this->json(['uuid' => $user->getUuid()]);
    }
}
