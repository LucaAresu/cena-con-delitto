<?php

declare(strict_types=1);

namespace App\CenaConDelitto\UserArea\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class HomeController extends AbstractController
{

    public function homepage(): Response
    {
        return $this->render('base.html.twig');
    }

    public function test(HubInterface $hub): Response
    {
        $update = new Update(
            'https://example.com/books/1',
            json_encode(['status' => 'OutOfStock'])
        );

        $hub->publish($update);

        return new Response('published!');
    }
}
