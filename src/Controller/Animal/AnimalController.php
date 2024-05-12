<?php

namespace App\Controller\Animal;

use App\Controller\SuperCrudController;
use App\Entity\Animal;
use App\Entity\AnimalImage;
use App\Repository\AnimalHabitatRepository;
use App\Repository\AnimalRepository;
use App\Services\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class AnimalController extends AbstractController
{
    private array $context;
    public function __construct(
        private readonly AnimalRepository    $animalRepository,
        private AnimalHabitatRepository $animalHabitatRepository,
        private readonly SuperCrudController $controller,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly ValidationService   $validationService,



    ) {
        $this->context = (new ObjectNormalizerContextBuilder())
            ->withGroups('animal_info')
            ->toArray();
    }
    #[Route('api/animal', 'get_animals', methods: 'GET')]
    public function getAnimals(): JsonResponse {
        return $this->controller->getAll($this->animalRepository, context: $this->context);
    }

    #[Route('api/animal/{id}', 'get_animal', methods: 'GET')]
    public function getAnimal($id): JsonResponse {
        return $this->controller->get($this->animalRepository, $id, context: $this->context);
    }

    #[Route('api/animal', 'create_animal', methods: 'POST')]
    public function create(Request $request): JsonResponse {

        $jsonData = $request->getContent();

        $newElement = $this->serializer->deserialize($jsonData, Animal::class, 'json', [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['animalImages'],
        ]);

        $validationResult = $this->validationService->validateEntity($newElement);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $post_data = json_decode($request->getContent(), true);
        $animalHabitat = $post_data['animalHabitat'];

        $habitat = $this->animalHabitatRepository->findOneBy(['name' => $animalHabitat]);
        $newElement->setAnimalHabitat($habitat);

        $this->em->persist($newElement);
        $this->em->flush();

        $newElementData = $this->serializer->normalize($newElement, 'json', $this->context);
        return $this->json($newElementData);
    }

    #[Route('api/animal/{id}', 'update_animal', methods: 'PUT')]
    public function update(Request $request, $id): JsonResponse {
        $post_data = json_decode($request->getContent(), true);
        $animal = $this->animalRepository->find($id);
        $animal->setName($post_data['name']);
        $animal->setSpecies($post_data['species']);
        $animal->setVetReview($post_data['vet_review']);
        $animal->setDetails($post_data['details']);

        $habitat = $this->animalHabitatRepository->findOneBy(['name' => $post_data['animalHabitat']]);
        $animal->setAnimalHabitat($habitat);

        $this->em->persist($animal);
        $this->em->flush();

        $animalData = $this->serializer->normalize($animal, 'json', $this->context);
        return $this->json($animalData);
    }

    #[Route('api/animal/{id}', 'delete_animal', methods: 'DELETE')]
    public function delete($id): JsonResponse {
        $animal = $this->animalRepository->find($id);
        if (count($animal->getAnimalImages())) {
            foreach ($animal->getAnimalImages() as $animalImage) {
                $imageFileName = $animalImage->getImageFileName();
                $imageDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/';
                $imagePath = $imageDirectory . $imageFileName;

                $filesystem = new Filesystem();
                $filesystem->remove($imagePath);

                $this->em->remove($animalImage);
                $this->em->flush();
            }
        }
        return $this->controller->delete($this->animalRepository, $id, context: $this->context);
    }

    // Habitat
    private function putHabitat($id, $animalHabitat) {
        $habitat = $this->animalHabitatRepository->findOneBy(['name' => $animalHabitat]);
        $this->em->persist($habitat);
        $animal = $this->animalRepository->find($id);

        if ($habitat) {
            $animal->setAnimalHabitat($habitat);
            $this->em->flush();
            return new JsonResponse(['message' => 'Animal have a new habitat']);
        }
        return new JsonResponse(['error' => 'Habitat doesn\'t found' ]);
    }
}