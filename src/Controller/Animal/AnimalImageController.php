<?php

namespace App\Controller\Animal;

use App\Entity\AnimalImage;
use App\Repository\AnimalImageRepository;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;


class AnimalImageController extends AbstractController
{
    private array $context;

    public function __construct(
        private readonly AnimalRepository $animalRepository,
        private readonly AnimalImageRepository $animalImageRepository,
        private EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,

    ) {
        $this->context = (new ObjectNormalizerContextBuilder())
            ->withGroups('animal_info')
            ->toArray();
    }
    #[Route('api/animal/{animalId}/image', name: 'animal_image_get', methods: 'GET')]
    public function get($animalId): Response
    {
        $animal = $this->animalRepository->find($animalId);
        $animalImages = $animal->getAnimalImages();

        $images = [];

        if (count($animalImages)) {
            foreach ($animalImages as $image) {
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/' . $image->getImageFileName();

                $imageContent = file_get_contents($imagePath);

                if ($imageContent === false) {
                    return new JsonResponse(['error' => 'Error reading image file']);
                }

                $base64Image = base64_encode($imageContent);

                $imageItem = ['id' => $image->getId(), 'file' => $base64Image];

                $images[] = $imageItem;
            }
            $response =  new JsonResponse($images);
            $response->headers->set('Content-Type', 'image/jpeg');
            return $response;
        }


        return new JsonResponse(['error' => 'Image doesn\'t found']);
    }
    #[Route('api/image/{habitatName}', name: 'animal_image_get_all_habitat', methods: 'GET')]
    public function getAll($habitatName): Response {
        $images = [];
        $animals = $this->animalRepository->findAllByHabitat($habitatName);


        foreach ($animals as $animal ) {
            $animalImages = $animal->getAnimalImages();
            $files = [];

            foreach ($animalImages as $image) {
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/' . $image->getImageFileName();

                $imageContent = file_get_contents($imagePath);

                if ($imageContent === false) {
                    return new JsonResponse(['error' => 'Error reading image file']);
                }

                $base64Image = base64_encode($imageContent);
                $files[] = $base64Image;


            }
            $imageItem = ['animal' => $animal->getName(), 'animalSpecies' => $animal->getSpecies(), 'animalState' => $animal->getVetReview(), 'animalHabitat' => $animal->getAnimalHabitatName(), 'files' => $files];
            $images[] = $imageItem;
        }

        $response =  new JsonResponse($images);
        return $response;
    }

    #[Route('api/image', name: 'animal_image_get_all', methods: 'GET')]
    public function getAllImages(): Response {
        $images = [];
        $animals = $this->animalRepository->findAll();


        foreach ($animals as $animal ) {
            $animalImages = $animal->getAnimalImages();
            $files = [];

            foreach ($animalImages as $image) {
                $imagePath = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/' . $image->getImageFileName();

                $imageContent = file_get_contents($imagePath);

                if ($imageContent === false) {
                    return new JsonResponse(['error' => 'Error reading image file']);
                }

                $base64Image = base64_encode($imageContent);
                $files[] = $base64Image;


            }
            $imageItem = ['animal' => $animal->getName(), 'animalSpecies' => $animal->getSpecies(), 'animalState' => $animal->getVetReview(), 'animalHabitat' => $animal->getAnimalHabitatName(), 'animalIdMongo' => $animal->getAnimalIdMongo(), 'files' => $files];
            $images[] = $imageItem;
        }

        $response =  new JsonResponse($images);
        return $response;
    }

    #[Route('api/animal/{id}/image', name: 'animal_image_post', methods: 'POST')]
    public function post(Request $request, $id): JsonResponse {
        $post_data = json_decode($request->getContent(), true);
        $uploadedFile = $post_data['file'];
        $uploadedFileName = $post_data['fileName'];

        preg_match('/^data:image\/(\w+);base64,/', $uploadedFile, $matches);
        $imageType = $matches[1];

        $base64Image = str_replace('data:image/'.$imageType.';base64,', '', $uploadedFile);
        $imageContent = base64_decode($base64Image);

        $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/';
        $filename = $uploadedFileName;

        $filePath = $uploadDirectory . $filename;

        file_put_contents($filePath, $imageContent);

        $animalImage = new AnimalImage();
        $animalImage->setImageFileName($filename);
        $this->em->persist($animalImage);

        $animal = $this->animalRepository->find($id);
        $animal->addAnimalImage($animalImage);

        $this->em->flush();


        $newElementData = $this->serializer->normalize($animal, 'json', $this->context);
        return $this->json($newElementData);
    }

    #[Route('api/animal/{animalId}/image/{imageId}', name: 'animal_image_delete', methods: 'DELETE')]
    public function delete($animalId, $imageId): JsonResponse {
        $image = $this->animalImageRepository->find($imageId);

        if (!$image) {
            return new JsonResponse(['error' => 'Image doesn\'t found'], Response::HTTP_NOT_FOUND);
        }

        $imageFileName = $image->getImageFileName();
        $imageDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/photos/';
        $imagePath = $imageDirectory . $imageFileName;

        $filesystem = new Filesystem();
        $filesystem->remove($imagePath);

        $this->em->remove($image);
        $this->em->flush();

        $animal = $this->animalRepository->find($animalId);

        $newElementData = $this->serializer->normalize($animal, 'json', $this->context);
        return $this->json($newElementData);

    }

}
