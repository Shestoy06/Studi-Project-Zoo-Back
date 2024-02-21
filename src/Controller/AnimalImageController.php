<?php

namespace App\Controller;

use App\Entity\AnimalImage;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;


class AnimalImageController extends AbstractController
{
    private array $context;

    public function __construct(
        private readonly AnimalRepository $animalRepository,
        private EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,

    ) {
        $this->context = (new ObjectNormalizerContextBuilder())
            ->withGroups('animal_info')
            ->toArray();
    }
    #[Route('api/animal/{id}/image', name: 'animal_image_get', methods: 'GET')]
    public function get($id): Response
    {
        $animal = $this->animalRepository->find($id);
        $animalImage = $animal->getAnimalImages()[0];

        $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/' . $animalImage->getImageFileName();

        $imageContent = file_get_contents($imagePath);

        $response = new Response($imageContent);

        $response->headers->set('Content-Type', 'image/jpeg');

        return $response;

    }
    #[Route('api/animal/{id}/image', name: 'animal_image_post', methods: 'POST')]
    public function post(Request $request, $id): JsonResponse {


        $uploadedFile = $request->files->get('photo');

        $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/';
        $uploadedFile->move($uploadDirectory, $uploadedFile->getClientOriginalName());

        $animalImage = new AnimalImage();
        $animalImage->setImageFileName($uploadedFile->getClientOriginalName());
        $this->em->persist($animalImage);

        $animal = $this->animalRepository->find($id);

        $animal->addAnimalImage($animalImage);

        $this->em->flush();



        return new JsonResponse(['message' => 'Photo uploaded successfully', 'photo' => $uploadedFile->getClientOriginalName()], Response::HTTP_OK);
    }
}
