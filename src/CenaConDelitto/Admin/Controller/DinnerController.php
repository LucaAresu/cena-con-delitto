<?php

declare(strict_types=1);

namespace CenaConDelitto\Admin\Controller;

use CenaConDelitto\Admin\Dto\CreateDinnerRequest;
use CenaConDelitto\Admin\UseCase\CreateDinner;
use CenaConDelitto\Shared\Entity\Dinner;
use CenaConDelitto\Shared\Repository\DinnerRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DinnerController extends AbstractController
{
    #[Route('admin/cena', methods: ['POST'])]
    /** @infection-ignore-all */
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

    #[Route('admin/cena/{uuid}', name: 'admin_dinner_delete', methods: ['DELETE'])]
    #[OA\Delete(
        summary: 'Deletes a dinner',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(
                    property: 'uuid',
                    title: 'uuid',
                    type: 'string',
                    example: '0186bc77-eeee-7fc2-ab19-bcc2602e7857'
                ),
            ])
        ),
        responses: [
            new OA\Response(response: 200, content: null, description: 'Successful response'),
            new OA\Response(response: 404, content: null, description: 'When dinner does not exist')
        ]
    )]
    public function delete(Dinner $dinner, DinnerRepository $dinnerRepository): Response
    {
        $dinnerRepository->remove($dinner);
        return $this->json(null);
    }

    #[Route('admin/cena/{uuid}', name: 'admin_dinner_get', methods: ['GET'])]
    public function get(Dinner $dinner): Response
    {
        return $this->json($dinner);
    }
}
