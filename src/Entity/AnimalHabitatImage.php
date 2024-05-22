<?php

namespace App\Entity;

use App\Repository\AnimalHabitatImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AnimalHabitatImageRepository::class)]
class AnimalHabitatImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('habitat_info')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('habitat_info')]
    private ?string $ImageFileName = null;

    #[ORM\ManyToOne(inversedBy: 'animalHabitatImages')]
    private ?AnimalHabitat $habitat = null;

    #[ORM\Column(length: 10000000, nullable: true)]
    #[Groups(['habitat_info'])]
    private ?string $imageEncoded = null;

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

    public function getImageEncoded(): ?string
    {
        return $this->imageEncoded;
    }

    public function setImageEncoded(string $imageEncoded): static
    {
        $this->imageEncoded = $imageEncoded;

        return $this;
    }

    public function getHabitat(): ?AnimalHabitat
    {
        return $this->habitat;
    }

    public function setHabitat(?AnimalHabitat $habitat): static
    {
        $this->habitat = $habitat;

        return $this;
    }
}
