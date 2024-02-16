<?php

namespace App\Entity;

use App\Repository\AnimalImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AnimalImageRepository::class)]
class AnimalImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('animal_info')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('animal_info')]
    private ?string $ImageFileName = null;

    #[ORM\ManyToOne(inversedBy: 'animalImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFileName(): ?string
    {
        return $this->ImageFileName;
    }

    public function setImageFileName(string $ImageFileName): static
    {
        $this->ImageFileName = $ImageFileName;

        return $this;
    }

    public function getAnimalId(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimalId(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }
}