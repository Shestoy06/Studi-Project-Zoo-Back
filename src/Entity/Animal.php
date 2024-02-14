<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert; // Add this line


#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $species = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $habitatArea = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $health_state = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $vet_review = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $food_type = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $food_quantity = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $last_review = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $details = null;

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

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getHabitatArea(): ?string
    {
        return $this->habitatArea;
    }

    public function setHabitatArea(string $habitatArea): static
    {
        $this->habitatArea = $habitatArea;

        return $this;
    }

    public function getHealthState(): ?string
    {
        return $this->health_state;
    }

    public function setHealthState(string $health_state): static
    {
        $this->health_state = $health_state;

        return $this;
    }

    public function getVetReview(): ?string
    {
        return $this->vet_review;
    }

    public function setVetReview(?string $vet_review): static
    {
        $this->vet_review = $vet_review;

        return $this;
    }

    public function getFoodType(): ?string
    {
        return $this->food_type;
    }

    public function setFoodType(string $food_type): static
    {
        $this->food_type = $food_type;

        return $this;
    }

    public function getFoodQuantity(): ?string
    {
        return $this->food_quantity;
    }

    public function setFoodQuantity(string $food_quantity): static
    {
        $this->food_quantity = $food_quantity;

        return $this;
    }

    public function getLastReview(): ?\DateTimeInterface
    {
        return $this->last_review;
    }

    public function setLastReview(\DateTimeInterface $last_review): static
    {
        $this->last_review = $last_review;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function setProps(
        Animal $animal
    ) : void {
        $this->setName($animal->name);
        $this->setSpecies($animal->species);
        $this->setHabitatArea($animal->habitatArea);
        $this->setHealthState($animal->health_state);
        $this->setVetReview($animal->vet_review);
        $this->setFoodType($animal->food_type);
        $this->setFoodQuantity($animal->food_quantity);
        $this->setLastReview($animal->last_review);
        $this->setDetails($animal->details);
    }
}
