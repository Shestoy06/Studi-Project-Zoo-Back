<?php

namespace App\Controller\Animal;

use App\Controller\SuperCrudController;
use App\Entity\AnimalFood;
use App\Entity\AnimalReview;
use App\Repository\AnimalFoodRepository;
use App\Repository\AnimalRepository;
use App\Repository\AnimalReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class AnimalReviewController extends AbstractController
{
    private $context = null;

    public function __construct(
        private EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly AnimalRepository $animalRepository,
        private readonly SuperCrudController $controller,
        private readonly AnimalReviewRepository $animalReviewRepository,


    ) {
        $this->context = (new ObjectNormalizerContextBuilder())
            ->withGroups('animal_info')
            ->toArray();
    }
    #[Route('api/review', name: 'app_animal_review', methods: 'POST')]
    public function post(Request $request): JsonResponse {

        $post_data = json_decode($request->getContent(), true);

        $animalReview = new AnimalReview();
        $animalReview->setReview($post_data['review']);
        $dateString = $post_data['date'];
        $animalId = $post_data['animalId'];

        $animalReview->setDate(new \DateTimeImmutable($dateString));

        $this->em->persist($animalReview);

        $animal = $this->animalRepository->find($animalId);
        $animal->addAnimalReview($animalReview);

        $this->em->flush();



        $animalData = $this->serializer->normalize($animalReview, 'json', $this->context);
        return $this->json($animalData);
    }

    #[Route('api/review', name: 'get_all_animal_review', methods: 'GET')]
    public function getAll(): JsonResponse {
        return $this->controller->getAll($this->animalReviewRepository, $this->context);
    }

    #[Route('api/review/{id}', name: 'delete_review', methods: 'DELETE')]
    public function delete($id): JsonResponse {
        return $this->controller->delete($this->animalReviewRepository, $id, $this->context);
    }

    #[Route('api/review/{id}', name: 'update_review', methods: 'PUT')]
    public function update(Request $request, $id): JsonResponse {

        $post_data = json_decode($request->getContent(), true);

        $animalReview = $this->animalReviewRepository->find($id);
        $animalReview->setReview($post_data['review']);
        $dateString = $post_data['date'];
        $animalId = $post_data['animalId'];

        $animalReview->setDate(new \DateTimeImmutable($dateString));

        $this->em->persist($animalReview);


        if($post_data['animalId'] != $animalReview->getAnimal()->getId()) {
            $animal = $this->animalRepository->find($post_data['animalId']);
            $animal->addAnimalReview($animalReview);
        }
        $this->em->flush();

        $animalData = $this->serializer->normalize($animalReview, 'json', $this->context);
        return $this->json($animalData);
    }
}
