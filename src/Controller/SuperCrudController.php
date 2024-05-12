<?php

namespace App\Controller;

use App\Controller\Animal\AnimalImageController;
use App\Entity\Animal;
use App\Services\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class SuperCrudController extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidationService   $validationService,
        private readonly EntityManagerInterface $em

    ) {

    }

    public function get(EntityRepository $repository, $id, array $context = []): JsonResponse {
        $element = $repository->find($id);

        if (!$element) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        if($element instanceof Animal) {
            $element->setLastReview($element->getLastReview().format('d/m/Y'));
        }

        $elementData = $this->serializer->normalize($element, 'json', $context);
        return $this->json($elementData);
    }

    public function getAll(EntityRepository $repository, array $context = []): JsonResponse {
        $elements = $repository->findBy([], ['id' => 'ASC']);


        if (!$elements) {
            return new JsonResponse([], Response::HTTP_OK);
        }


        $elementsData = $this->serializer->normalize($elements, 'json', $context);
        return $this->json($elementsData);

    }

    public function getAllBy(EntityRepository $repository, string $criteria, string $value, array $context = []): JsonResponse {


        $elements = $repository->findBy(array([$criteria => $value]));

        if (!$elements) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }


        $elementsData = $this->serializer->normalize($elements, 'json', $context);
        return $this->json($elementsData);
    }

    public function create(Request $request, string $class, array $context = []): JsonResponse {
        $jsonData = $request->getContent();
        $newElement = $this->serializer->deserialize($jsonData, $class, 'json');

        $validationResult = $this->validationService->validateEntity($newElement);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $this->em->persist($newElement);
        $this->em->flush();

        $newElementData = $this->serializer->normalize($newElement, 'json', $context);
        return $this->json($newElementData);
    }

    public function update(EntityRepository $repository, Request $request, $id, string $class, array $context = []): JsonResponse {
        $jsonData = $request->getContent();
        $element = $repository->find($id);

        if (!$element) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $element->setProps($this->serializer->deserialize($jsonData, $class, 'json'));
        $this->em->flush();
        $elementData = $this->serializer->normalize($element, 'json', $context);
        return $this->json($elementData);
    }

    public function delete(EntityRepository $repository, $id, array $context = []): JsonResponse {
        $element = $repository->find($id);

        if (!$element) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        //$elementData = $this->serializer->normalize($element, 'json', $context);

        $this->em->remove($element);
        $this->em->flush();

        return new JsonResponse(['status' => 'Entity deleted'], Response::HTTP_OK);
    }
}
