<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PenRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PenRepository::class)]
class Pen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('pen:read')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('pen:read')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups('pen:read')]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    #[Groups('pen:read')]
    private ?string $description = null;

    #[ORM\Column(length: 10, unique: true)]
    #[Groups('pen:read')]
    private ?string $ref = null;

    #[ORM\ManyToOne(inversedBy: 'pens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('pen:read')]
    private ?Type $type = null;

    #[ORM\ManyToMany(targetEntity: Color::class, inversedBy: 'pens')]
    #[Groups('pen:read')]
    private Collection $colors;

    #[ORM\ManyToOne(inversedBy: 'pens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('pen:read')]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(inversedBy: 'pens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('pen:read')]
    private ?Material $material = null;

    public function __construct()
    {
        $this->colors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getType(): ?type
    {
        return $this->type;
    }

    public function setType(?type $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, color>
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(color $color): static
    {
        if (!$this->colors->contains($color)) {
            $this->colors->add($color);
        }

        return $this;
    }

    public function removeColor(color $color): static
    {
        $this->colors->removeElement($color);

        return $this;
    }

    public function getBrand(): ?brand
    {
        return $this->brand;
    }

    public function setBrand(?brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getMaterial(): ?material
    {
        return $this->material;
    }

    public function setMaterial(?material $material): static
    {
        $this->material = $material;

        return $this;
    }
}
