<?php

namespace App\Service;

use Faker\Factory;
use App\Entity\Pen;
use App\Repository\TypeRepository;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

class PenService 
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private MaterialRepository $materialRepository,
        private TypeRepository $typeRepository,
        private ColorRepository $colorRepository,
        private BrandRepository $brandRepository,
    ){}

    public function create(array $data): Pen
    {
        $faker = Factory::create();

        $pen = new Pen();
        $pen->setName($data['name']);
        $pen->setPrice($data['price']);
        $pen->setDescription($data['description']);
        $pen->setRef($faker->unique()->ean13);

        if (!empty($data['type'])) {
            $type = $this->typeRepository->find($data['type']);

            if (!$type) {
                throw new \Exception("Le type renseigné n'existe pas ");
            }

            $pen->setType($type);
        }

        if (!empty($data['material'])) {
            $material = $this->materialRepository->find($data['material']);

            if (!$material) {
                throw new \Exception("Le material renseigné n'existe pas ");
            }

            $pen->setMaterial($material);
        }

        if (!empty($data['brand'])) {
            $brand = $this->brandRepository->find($data['brand']);

            if (!$brand) {
                throw new \Exception("La marque renseigné n'existe pas ");
            }

            $pen->setBrand($brand);
        }

        if(!empty($data['color'])) {
            $color = $this->colorRepository->find($data['color']);

            if(!$color) {
                throw new \Exception("La couleur renseigné n'existe pas ");
            }

            $pen->addColor($color);
        }

        $this->entityManager->persist($pen);
        $this->entityManager->flush();

        return $pen;
    }

    public function createFromArray(array $data): Pen
    {
        return $this->create($data);
    }

    public function createFromJsonString(string $jsonString): Pen
    {
        $data = json_decode($jsonString, true);

        return $this->create($data);
    }

    public function updateWithJsonData(Pen $pen, string $jsonData): void
    {
        $data = json_decode($jsonData, true);

        $this->update($pen, $data);
    }

    public function update(Pen $pen, array $data): void
    {

        if(!empty($data['name'])) {
            $pen->setName($data['name']);
        }
        if(!empty($data['price'])) {
            $pen->setPrice($data['price']);
        }
        if(!empty($data['description'])) {
            $pen->setDescription($data['description']);
        }
        
        if (!empty($data['type'])) {
            $type = $this->typeRepository->find($data['type']);

            if (!$type) {
                throw new \Exception("Le type renseigné n'existe pas ");
            }

            $pen->setType($type);
        }

        if (!empty($data['material'])) {
            $material = $this->materialRepository->find($data['material']);

            if (!$material) {
                throw new \Exception("Le material renseigné n'existe pas ");
            }

            $pen->setMaterial($material);
        }

        if (!empty($data['brand'])) {
            $brand = $this->brandRepository->find($data['brand']);

            if (!$brand) {
                throw new \Exception("Le brand renseigné n'existe pas ");
            }

            $pen->setBrand($brand);
        }

        if(!empty($data['color'])) {
            $color = $this->colorRepository->find($data['color']);

            if(!$color) {
                throw new \Exception("La couleur renseigné n'existe pas ");
            }

            $pen->addColor($color);
        }

        $this->entityManager->persist($pen);
        $this->entityManager->flush();
    }
}