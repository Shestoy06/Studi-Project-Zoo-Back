<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Services\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PhpParser\Builder\Class_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SuperCrudController extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidationService   $validationService,
        private EntityManagerInterface $em

    ) {

    }
    public function getAll(EntityRepository $repository): JsonResponse {
        $elements = $repository->findAll();

        if (!$elements) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $elementsData = $this->serializer->normalize($elements);
        return $this->json($elementsData);

    }

    public function get(EntityRepository $repository, $id): JsonResponse {
        $element = $repository->find($id);

        if (!$element) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $elementData = $this->serializer->normalize($element);
        return $this->json($elementData);
    }

    public function create(Request $request, string $class): JsonResponse {
        $jsonData = $request->getContent();
        $newElement = $this->serializer->deserialize($jsonData, $class, 'json');

        $validationResult = $this->validationService->validateEntity($newElement);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $this->em->persist($newElement);
        $this->em->flush();

        $newElementData = $this->serializer->normalize($newElement);
        return $this->json($newElementData);
    }

    public function update(EntityRepository $repository, Request $request, $id, string $class): JsonResponse {
        $jsonData = $request->getContent();
        $element = $repository->find($id);

        if (!$element) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $element->setProps($this->serializer->deserialize($jsonData, $class, 'json'));
        $this->em->flush();
        return $this->json($element);
    }

    public function delete(EntityRepository $repository, $id): JsonResponse {
        $element = $this->$repository->find($id);

        if (!$element) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $elementData = $this->serializer->normalize($element);

        $this->em->remove($element);
        $this->em->flush();

        return $this->json($elementData);

    }
}
