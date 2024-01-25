<?php

namespace App\Controller;

use Faker\Factory;
use App\Entity\Pen;
use OpenApi\Attributes as OA;
use App\Repository\PenRepository;
use App\Repository\TypeRepository;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\MaterialRepository;
use App\Service\PenService;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/api', name: 'api_')]
class PenController extends AbstractController
{
    #[Route('/pens', name: 'app_pens', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Retourne tous les stylos.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Pen::class, groups: ['pen:read']))
        )
    )]
    #[OA\Tag(name: 'Stylos')]
    #[Security(name: 'Bearer')]
    public function index(PenRepository $penRepository): JsonResponse
    {
        $pens = $penRepository->findAll();

        return $this->json([
            'pens' => $pens,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/pen/{id}', name: 'app_pen_get', methods: ['GET'])]
    #[OA\Tag(name: 'Stylos')]
    public function get(Pen $pen): JsonResponse
    {
        return $this->json([
            'pen' => $pen,
        ], context: ['groups' => 'pen:read']);
    }

    #[Route('/pens', name: 'app_pen_add', methods: ['POST'])]
    #[OA\Tag(name: 'Stylos')]
    public function add(
        Request $request,
        PenService $penService
    ): JsonResponse {
        try {
            $pen = $penService->createFromJsonString($request->getContent());

            return $this->json($pen, context: ['groups' => 'pen:read']);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/pen/{id}', name: 'app_pen_update', methods: ['PUT','PATCH'])]
    #[OA\Tag(name: 'Stylos')]
    public function update(
        Pen $pen,
        Request $request,
        PenService $penService
    ): JsonResponse {
        try {
            $penService->updateWithJsonData($pen, $request->getContent());

            return $this->json($pen, context: ['groups' => 'pen:read']);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/pen/{id}', name: 'app_pen_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Stylos')]
    public function delete(Pen $pen, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $entityManager->remove($pen);
            $entityManager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Le stylo a bien été supprimé'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
