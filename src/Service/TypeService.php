<?php

use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;

class TypeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(array $data): Type
    {
        $type = new Type();
        $type->setName($data['name']);

        $this->entityManager->persist($type);
        $this->entityManager->flush();

        return $type;
    }

    public function createFromJsonString(string $jsonString): Type
    {
        $data = json_decode($jsonString, true);

        return $this->create($data);
    }

    public function updateWithJsonData(Type $type, string $jsonString): void
    {
        $data = json_decode($jsonString, true);

        return $this->update($type, $data);
    }

    public function update(Type $type, array $data): void
    {
        $type->setName($data['name']);

        $this->entityManager->persist($type);
        $this->entityManager->flush();
    }
}
