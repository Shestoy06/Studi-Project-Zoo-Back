<?php

namespace App\Controller\Animal;

use App\Controller\SuperCrudController;
use App\Entity\AnimalFood;
use App\Repository\AnimalFoodRepository;
use App\Repository\AnimalImageRepository;
use App\Repository\AnimalRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;


class AnimalFoodController extends AbstractController
{
    private $context = null;

    public function __construct(
        private EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly AnimalRepository $animalRepository,
        private readonly SuperCrudController $controller,
        private readonly AnimalFoodRepository $animalFoodRepository,


    ) {
        $this->context = (new ObjectNormalizerContextBuilder())
            ->withGroups('animal_info')
            ->toArray();
    }
    #[Route('api/food/animal/{id}', name: 'app_animal_food', methods: 'POST')]
    public function post(Request $request, $id): JsonResponse {

        $post_data = json_decode($request->getContent(), true);

        $animalFood = new AnimalFood();
        $animalFood->setFoodQuantity($post_data['food_quantity']);
        $animalFood->setFoodType($post_data['food_type']);
        $dateString = $post_data['dateTime'];
        $dateTime = new DateTime($dateString);
       // $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i', $dateString);

        $animalFood->setDateTime(new \DateTimeImmutable($dateString));
        //return new JsonResponse(['datetime' => $animalFood->getDateTime() ]);

        $this->em->persist($animalFood);

        $animal = $this->animalRepository->find($id);
        $animal->addAnimalFood($animalFood);

        $this->em->flush();



        $animalData = $this->serializer->normalize($animalFood, 'json', $this->context);
        return $this->json($animalData);
    }

    #[Route('api/food', name: 'get_all_food', methods: 'GET')]
    public function getAll(): JsonResponse {
        return $this->controller->getAll($this->animalFoodRepository, $this->context);
    }

    #[Route('api/food/{id}', name: 'delete_food', methods: 'DELETE')]
    public function delete($id): JsonResponse {
        return $this->controller->delete($this->animalFoodRepository, $id, $this->context);
    }

    #[Route('api/food/{id}', name: 'update_food', methods: 'PUT')]
    public function update(Request $request, $id): JsonResponse {

        $post_data = json_decode($request->getContent(), true);
        $animalFood = $this->animalFoodRepository->find($id);
        $animalFood->setFoodQuantity($post_data['food_quantity']);
        $animalFood->setFoodType($post_data['food_type']);
        $dateString = $post_data['dateTime'];
        $animalFood->setDateTime(new \DateTimeImmutable($dateString));

        $this->em->persist($animalFood);

        if($post_data['animalId'] != $animalFood->getAnimal()->getId()) {
            $animal = $this->animalRepository->find($post_data['animalId']);
            $animal->addAnimalFood($animalFood);
        }
        $this->em->flush();

        $animalData = $this->serializer->normalize($animalFood, 'json', $this->context);
        return $this->json($animalData);
    }
}
