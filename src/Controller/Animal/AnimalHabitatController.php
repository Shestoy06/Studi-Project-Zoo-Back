<?php

namespace App\Controller\Animal;

use App\Controller\SuperCrudController;
use App\Entity\AnimalHabitat;
use App\Entity\Services;
use App\Repository\AnimalHabitatRepository;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class AnimalHabitatController extends AbstractController
{
    private $context = null;
    public function __construct(
        private AnimalRepository $animalRepository,
        private AnimalHabitatRepository $animalHabitatRepository,
        private SuperCrudController $controller,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $em


    )
    {
        $this->context = (new ObjectNormalizerContextBuilder())
            ->withGroups(['habitat_info'])
            ->toArray();
    }

    #[Route('api/animal/habitat/{habitatName}', name: 'app_animal_habitat', methods: 'GET')]
    public function getAnimalsByHabitat($habitatName): JsonResponse {
        $elements = $this->animalRepository->findAllByHabitat($habitatName);

        if (!$elements) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }


        $elementsData = $this->serializer->normalize($elements, 'json', $this->context);
        return $this->json($elementsData);
    }

    #[Route('api/habitat', name: 'app_get_animal_habitates', methods: 'GET')]
    public function getHabitates(): JsonResponse {
        return $this->controller->getAll($this->animalHabitatRepository, $this->context);
    }

    #[Route('api/habitats-photos', name: 'get_habitats_with_photos', methods: 'GET')]
    public function getHabitatsWithPhotos(): Response {
        $images = [];
        $habitats = $this->animalHabitatRepository->findAll();


        foreach ($habitats as $habitat ) {
            $habitatImages = $habitat->getAnimalHabitatImages();
            $files = [];
            $habitatAnimals = $habitat->getAnimals();
            $animalsNames = [];

            foreach ($habitatImages as $image) {
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/' . $image->getImageFileName();

                $imageContent = file_get_contents($imagePath);

                if ($imageContent === false) {
                    return new JsonResponse(['error' => 'Error reading image file']);
                }

                $base64Image = base64_encode($imageContent);
                $files[] = $base64Image;
            }

            foreach ($habitatAnimals as $animal) {
                $animalsNames[] = $animal->getName();
            }

            $imageItem = ['habitat' => $habitat->getName(), 'comment' => $habitat->getComment(), 'description' => $habitat->getDescription(), 'files' => $files, 'animals' => $animalsNames];
            $images[] = $imageItem;
        }

        $response =  new JsonResponse($images);
        return $response;
    }

    #[Route('api/habitat', name: 'post_habitat', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        return $this->controller->create($request, AnimalHabitat::class);
    }

    #[Route('api/habitat/{id}', name: 'update_habitat', methods: 'PUT')]
    public function update(Request $request, $id): JsonResponse
    {
        return $this->controller->update($this->animalHabitatRepository,$request, $id, AnimalHabitat::class, $this->context);
    }

    #[Route('api/habitat/{id}', name: 'delete_habitat', methods: 'DELETE')]
    public function delete(Request $request, $id): JsonResponse
    {
        $habitat = $this->em->getRepository(AnimalHabitat::class)->find($id);

        if (!$habitat) {
            throw $this->createNotFoundException('Habitat not found');
        }

        $animals = $habitat->getAnimals();

        foreach ($animals as $animal) {
            $animal->setAnimalHabitat(null);
        }

        $this->em->remove($habitat);
        $this->em->flush();

        return new JsonResponse(['message' => 'Habitat deleted'], Response::HTTP_OK);
    }
}
