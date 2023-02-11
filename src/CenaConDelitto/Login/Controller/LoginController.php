<?php

declare(strict_types=1);

namespace CenaConDelitto\Login\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login_page', methods: 'GET')]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }
}
