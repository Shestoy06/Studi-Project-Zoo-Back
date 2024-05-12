<?php

namespace App\Entity;

use App\Repository\AnimalFoodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AnimalFoodRepository::class)]
class AnimalFood
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('animal_info')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('animal_info')]
    private ?string $food_type = null;

    #[ORM\Column(length: 255)]
    #[Groups('animal_info')]
    private ?string $food_quantity = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups('animal_info')]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\ManyToOne(inversedBy: 'animalFood')]
    #[Groups('animal_info')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Animal $animal = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function setProps(
        AnimalFood $animalFood
    ) : void {
        $this->setFoodType($animalFood->food_type);
        $this->setFoodQuantity($animalFood->food_quantity);
        $this->setAnimal($animalFood->animal);
        $this->setDateTime($animalFood->dateTime);
    }
}
