<?php

namespace App\Entity;

use App\Controller\Animal\AnimalController;
use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert; // Add this line


#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('animal_info')]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups(['animal_info', 'habitat_info'])]
    private ?string $name = null;
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Groups(['animal_info', 'habitat_info'])]
    private ?string $species = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[Groups('animal_info')]
    private ?AnimalHabitat $animalHabitat = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['animal_info', 'habitat_info'])]
    private ?string $vet_review = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups('animal_info')]
    private ?\DateTimeInterface $last_review = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('animal_info')]
    private ?string $details = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('animal_info')]
    private ?string $animalIdMongo = null;

    #[ORM\OneToMany(targetEntity: AnimalImage::class, mappedBy: 'animal')]
    #[Groups(['animal_info', 'habitat_info'])]
    private Collection $animalImages;

    #[ORM\OneToMany(targetEntity: AnimalReview::class, mappedBy: 'animal', cascade: ["remove"])]
    private Collection $animalReviews;

    #[ORM\OneToMany(targetEntity: AnimalFood::class, mappedBy: 'animal', cascade: ["remove"])]
    private Collection $animalFood;

    public function __construct()
    {
        $this->animalImages = new ArrayCollection();
        $this->animalReviews = new ArrayCollection();
        $this->animalFood = new ArrayCollection();
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

    public function getSpecies(): ?string
    {
        return $this->species;
    }

    public function setSpecies(string $species): static
    {
        $this->species = $species;

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

    public function getLastReview(): ?\DateTimeInterface
    {
        if ($this->animalReviews->isEmpty()) {
            return null;
        }

        $reviews = $this->animalReviews->toArray();
        $lastReview = $reviews[count($reviews) - 1]->getDate();
        return $lastReview;
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

    public function getAnimalIdMongo(): ?string
    {
        return $this->animalIdMongo;
    }

    public function setAnimalIdMongo(?string $animalIdMongo): static
    {
        $this->animalIdMongo = $animalIdMongo;

        return $this;
    }
    public function setProps(
        Animal $animal
    ) : void {
        $this->setName($animal->name);
        $this->setSpecies($animal->species);
        $this->setVetReview($animal->vet_review);
        $this->setLastReview($animal->last_review);
        $this->setDetails($animal->details);
    }

    /**
     * @return Collection<int, AnimalImage>
     */
    public function getAnimalImages(): Collection
    {
        return $this->animalImages;
    }

    public function addAnimalImage(AnimalImage $animalImage): static
    {
        if (!$this->animalImages->contains($animalImage)) {
            $this->animalImages->add($animalImage);
            $animalImage->setAnimalId($this);
        }

        return $this;
    }

    public function removeAnimalImage(AnimalImage $animalImage): static
    {
        if ($this->animalImages->removeElement($animalImage)) {
            // set the owning side to null (unless already changed)
            if ($animalImage->getAnimalId() === $this) {
                $animalImage->setAnimalId(null);
            }
        }

        return $this;
    }

    public function getAnimalHabitat(): ?AnimalHabitat
    {
        return $this->animalHabitat;
    }

    public function getAnimalHabitatName(): string {
        return $this->animalHabitat->getName();
    }
    public function setAnimalHabitat(?AnimalHabitat $animalHabitat): static
    {
        $this->animalHabitat = $animalHabitat;

        return $this;
    }

    /**
     * @return Collection<int, AnimalReview>
     */
    public function getAnimalReviews(): Collection
    {
        return $this->animalReviews;
    }

    public function addAnimalReview(AnimalReview $animalReview): static
    {
        if (!$this->animalReviews->contains($animalReview)) {
            $this->animalReviews->add($animalReview);
            $animalReview->setAnimal($this);
        }

        return $this;
    }

    public function removeAnimalReview(AnimalReview $animalReview): static
    {
        if ($this->animalReviews->removeElement($animalReview)) {
            // set the owning side to null (unless already changed)
            if ($animalReview->getAnimal() === $this) {
                $animalReview->setAnimal(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnimalFood>
     */
    public function getAnimalFood(): Collection
    {
        return $this->animalFood;
    }

    public function addAnimalFood(AnimalFood $animalFood): static
    {
        if (!$this->animalFood->contains($animalFood)) {
            $this->animalFood->add($animalFood);
            $animalFood->setAnimal($this);
        }

        return $this;
    }

    public function removeAnimalFood(AnimalFood $animalFood): static
    {
        if ($this->animalFood->removeElement($animalFood)) {
            // set the owning side to null (unless already changed)
            if ($animalFood->getAnimal() === $this) {
                $animalFood->setAnimal(null);
            }
        }

        return $this;
    }
}
