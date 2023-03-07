<?php

declare(strict_types=1);

namespace CenaConDelitto\Admin\Controller;

use CenaConDelitto\Admin\Dto\CreateDinnerRequest;
use CenaConDelitto\Admin\UseCase\CreateDinner;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DinnerController extends AbstractController
{
    #[Route('admin/cena', methods: ['POST'])]
    /** @infection-ignore-all  */
    #[OA\Post(
        summary: 'Create A Dinner',
        requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: '#/components/schemas/CreateDinnerRequest')),
        responses: [
            new OA\Response(
                response: 200,
                content: new OA\JsonContent(ref: '#/components/schemas/Dinner'),
                description: 'The dinner model',
            ),
            new OA\Response(
                response: 400,
                content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorsResponse'),
                description: 'a message of validation errors response',
            ),
        ],
    )]
    public function createDinner(CreateDinnerRequest $request, CreateDinner $createDinner): Response
    {
        $dinner = $createDinner->execute($request);

        return $this->json($dinner);
    }
}
