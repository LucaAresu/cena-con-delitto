<?php

declare(strict_types=1);

namespace CenaConDelitto\UserArea\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/cena', name: 'home')]
    public function homepage(): Response
    {
        return $this->render('base.html.twig');
    }

    // #[Route('/test', name: 'test')]
    // public function test(HubInterface $hub): Response
    // {
    //     $update = new Update(
    //         'https://example.com/books/1',
    //         json_encode(['status' => 'OutOfStock'], JSON_THROW_ON_ERROR)
    //     );
    //
    //     $hub->publish($update);
    //
    //     return new Response('published!');
    // }
}
