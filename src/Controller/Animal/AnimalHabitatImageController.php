<?php

namespace App\Controller\Animal;

use App\Entity\AnimalHabitatImage;
use App\Entity\AnimalImage;
use App\Repository\AnimalHabitatImageRepository;
use App\Repository\AnimalHabitatRepository;
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

class AnimalHabitatImageController extends AbstractController
{
    private array $context;

    public function __construct(
        private readonly AnimalHabitatRepository $animalHabitatRepository,
        private readonly AnimalHabitatImageRepository $animalHabitatImageRepository,
        private EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,

    ) {
        $this->context = (new ObjectNormalizerContextBuilder())
            ->withGroups('habitat_info')
            ->toArray();
    }
    #[Route('api/habitat/{id}/image', name: 'habitat_image_get', methods: 'GET')]
    public function get($id): Response
    {
        $habitat = $this->animalHabitatRepository->find($id);

        $habitatImages = $habitat->getAnimalHabitatImages();



        $images = [];

        if (count($habitatImages)) {
            foreach ($habitatImages as $image) {
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
    #[Route('api/habitat/{id}/image', name: 'habitat_image_post', methods: 'POST')]
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

        $habitatImage = new AnimalHabitatImage();
        $habitatImage->setImageFileName($filename);
        $this->em->persist($habitatImage);

        $habitat = $this->animalHabitatRepository->find($id);
        $habitat->addAnimalHabitatImage($habitatImage);

        $this->em->flush();


        $newElementData = $this->serializer->normalize($habitat, 'json', $this->context);
        return $this->json($newElementData);
    }

    #[Route('api/habitat/{id}/image/{imageId}', name: 'habitat_image_delete', methods: 'DELETE')]
    public function delete($id, $imageId): JsonResponse {
        $image = $this->animalHabitatImageRepository->find($imageId);

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

        $habitat = $this->animalHabitatRepository->find($id);

        $newElementData = $this->serializer->normalize($habitat, 'json', $this->context);
        return $this->json($newElementData);
    }
}
