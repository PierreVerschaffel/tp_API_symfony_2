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
        EntityManagerInterface $entityManager,
        Request $request,
        TypeRepository $typeRepository,
        MaterialRepository $materialRepository,
        BrandRepository $brandRepository,
        ColorRepository $colorRepository
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            $faker = Factory::create();

            $pen = new Pen();
            $pen->setName($data['name']);
            $pen->setPrice($data['price']);
            $pen->setDescription($data['description']);
            $pen->setRef($faker->unique()->ean13);

            if (!empty($data['type'])) {
                $type = $typeRepository->find($data['type']);

                if (!$type) {
                    throw new \Exception("Le type renseigné n'existe pas ");
                }

                $pen->setType($type);
            }

            if (!empty($data['material'])) {
                $material = $materialRepository->find($data['material']);

                if (!$material) {
                    throw new \Exception("Le material renseigné n'existe pas ");
                }

                $pen->setMaterial($material);
            }

            if (!empty($data['brand'])) {
                $brand = $brandRepository->find($data['brand']);

                if (!$brand) {
                    throw new \Exception("La marque renseigné n'existe pas ");
                }

                $pen->setBrand($brand);
            }

            if(!empty($data['color'])) {
                $color = $colorRepository->find($data['color']);

                if(!$color) {
                    throw new \Exception("La couleur renseigné n'existe pas ");
                }
            }

            $entityManager->persist($pen);
            $entityManager->flush();

            return $this->json([
                'pen' => $pen,
            ], context: ['groups' => 'pen:read']);
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
        EntityManagerInterface $entityManager,
        Request $request,
        TypeRepository $typeRepository,
        MaterialRepository $materialRepository,
        BrandRepository $brandRepository
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            $pen->setName($data['name']);
            $pen->setPrice($data['price']);
            $pen->setDescription($data['description']);

            if (!empty($data['type'])) {
                $type = $typeRepository->find($data['type']);

                if (!$type) {
                    throw new \Exception("Le type renseigné n'existe pas ");
                }

                $pen->setType($type);
            }

            if (!empty($data['material'])) {
                $material = $materialRepository->find($data['material']);

                if (!$material) {
                    throw new \Exception("Le material renseigné n'existe pas ");
                }

                $pen->setMaterial($material);
            }

            if (!empty($data['brand'])) {
                $brand = $brandRepository->find($data['brand']);

                if (!$brand) {
                    throw new \Exception("Le brand renseigné n'existe pas ");
                }

                $pen->setBrand($brand);
            }

            // $entityManager->persist($pen);
            $entityManager->flush();

            return $this->json([
                'pen' => $pen,
            ], context: ['groups' => 'pen:read']);
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
