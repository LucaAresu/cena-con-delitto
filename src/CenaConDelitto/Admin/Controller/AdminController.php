<?php

declare(strict_types=1);

namespace CenaConDelitto\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/', name: 'admin_home')]
    public function home(): Response
    {
        return new Response('admin home');
    }
}
