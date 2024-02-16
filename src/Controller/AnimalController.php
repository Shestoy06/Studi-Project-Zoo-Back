<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use App\Services\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class AnimalController extends AbstractController
{
    private array $context;
    public function __construct(
        private readonly AnimalRepository    $animalRepository,
        private readonly SuperCrudController $controller
    ) {
        $this->context = (new ObjectNormalizerContextBuilder())
            ->withGroups('animal_info')
            ->toArray();
    }
    #[Route('/animal', 'get_animals', methods: 'GET')]
    public function getAnimals(): JsonResponse {
        return $this->controller->getAll($this->animalRepository, context: $this->context);
    }

    #[Route('api/animal/{id}', 'get_animal', methods: 'GET')]
    public function getAnimal($id): JsonResponse {
        return $this->controller->get($this->animalRepository, $id, context: $this->context);
    }

    #[Route('api/animal', 'create_animal', methods: 'POST')]
    public function create(Request $request): JsonResponse {
        return $this->controller->create($request, Animal::class, context: $this->context);
    }

    #[Route('api/animal/{id}', 'update_animal', methods: 'PUT')]
    public function update(Request $request, $id): JsonResponse {
        return $this->controller->update($this->animalRepository, $request, $id, Animal::class, context: $this->context);
    }

    #[Route('api/animal/{id}', 'delete_animal', methods: 'DELETE')]
    public function delete($id): JsonResponse {
        return $this->controller->delete($this->animalRepository, $id, context: $this->context);
    }
}