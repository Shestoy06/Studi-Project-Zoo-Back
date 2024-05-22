<?php

namespace App\Entity;

use App\Repository\AnimalHabitatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AnimalHabitatRepository::class)]
class AnimalHabitat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('habitat_info')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['habitat_info', 'animal_info'])]
    private ?string $name = null;

    #[ORM\Column(length: 2000, nullable: true)]
    #[Groups('habitat_info')]
    private ?string $comment = null;

    #[ORM\Column(length: 2000, nullable: true)]
    #[Groups('habitat_info')]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'animalHabitat', cascade: ['detach'])]
    #[Groups('habitat_info')]
    private Collection $animals;

    #[ORM\OneToMany(targetEntity: AnimalHabitatImage::class, mappedBy: 'habitat', cascade: ["remove"])]
    #[Groups('habitat_info')]
    private Collection $animalHabitatImages;



    public function __construct()
    {
        $this->animals = new ArrayCollection();
        $this->animalHabitatImages = new ArrayCollection();
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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

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



    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): static
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setAnimalHabitat($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getAnimalHabitat() === $this) {
                $animal->setAnimalHabitat(null);
            }
        }

        return $this;
    }

    public function setProps(
        AnimalHabitat $animalHabitat
    ) : void {
        $this->setName($animalHabitat->name);
        $this->setComment($animalHabitat->comment);
        $this->setDescription($animalHabitat->description);
    }

    /**
     * @return Collection<int, AnimalHabitatImage>
     */
    public function getAnimalHabitatImages(): Collection
    {
        return $this->animalHabitatImages;
    }

    public function addAnimalHabitatImage(AnimalHabitatImage $animalHabitatImage): static
    {
        if (!$this->animalHabitatImages->contains($animalHabitatImage)) {
            $this->animalHabitatImages->add($animalHabitatImage);
            $animalHabitatImage->setHabitat($this);
        }

        return $this;
    }

    public function removeAnimalHabitatImage(AnimalHabitatImage $animalHabitatImage): static
    {
        if ($this->animalHabitatImages->removeElement($animalHabitatImage)) {
            // set the owning side to null (unless already changed)
            if ($animalHabitatImage->getHabitat() === $this) {
                $animalHabitatImage->setHabitat(null);
            }
        }

        return $this;
    }
}
