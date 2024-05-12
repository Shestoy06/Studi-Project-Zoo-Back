<?php

namespace App\Entity;

use App\Repository\AnimalReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AnimalReviewRepository::class)]
class AnimalReview
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('animal_info')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'animalReview')]
    #[Groups('animal_info')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Animal $animal = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups('animal_info')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 1000, nullable: true)]
    #[Groups('animal_info')]
    private ?string $review = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $Animal): static
    {
        $this->animal = $Animal;

        return $this;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): static
    {
        $this->review = $review;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->date = $Date;

        return $this;
    }
}
