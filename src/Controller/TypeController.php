<?php

namespace App\Controller;

use App\Entity\Type;
use OpenApi\Attributes as OA;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use TypeService;

#[Route('/api', name: 'api_')]
class TypeController extends AbstractController
{
    #[Route('/type', name: 'app_type', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne toutes les marques.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Type::class, groups: ['pen:read']))
        )
    )]
    #[OA\Tag(name: 'Type')]
    #[Security(name: 'Bearer')]
    public function index(
        TypeRepository $typeRepository
    ): Response {
        $types = $typeRepository->findAll();

        return $this->json([
            'types' => $types,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/type/{id}', name: 'app_type_get', methods: ['GET'])]
    #[OA\Tag(name: 'Type')]
    public function get(
        Type $type
    ): Response {
        return $this->json([
            'type' => $type,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/type', name: 'app_type_add', methods: ['POST'])]
    #[OA\Tag(name: 'Type')]
    public function add(
        Request $request,
        TypeService $typeService
    ): Response {

        $type = $typeService->createFromJsonString($request->getContent());

        return $this->json($type, context: ['groups' => 'pen:read']);
    }

    #[Route('/type/{id}', name: 'app_type_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Type')]
    public function update(
        Request $request,
        Type $type,
        TypeService $typeService
    ): Response {

        $typeService->updateWithJsonData($type, $request->getContent());

        return $this->json([
            'type' => $type,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/type/{id}', name: 'app_type_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Type')]
    public function delete(Type $type, EntityManagerInterface $em): Response
    {
        $em->remove($type);
        $em->flush();

        return $this->json([
            'message' => 'Type supprimé',
        ]);
    }
}
