<?php

namespace App\Controller;

use App\Entity\Services;
use App\Repository\AnimalHabitatRepository;
use App\Repository\AnimalRepository;
use App\Repository\ServicesRepository;
use App\Services\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ServicesController extends AbstractController
{
    public function __construct(
        private ServicesRepository $servicesRepository,
        private readonly SuperCrudController $controller,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly ValidationService   $validationService,
    ){}
    #[Route('api/services', name: 'get_services', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        return $this->controller->getAll($this->servicesRepository);
    }

    #[Route('api/services/{id}', name: 'get_service', methods: 'GET')]
    public function get($id): JsonResponse
    {
        return $this->controller->get($this->servicesRepository, $id);
    }

    #[Route('api/services', name: 'post_service', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        return $this->controller->create($request, Services::class);
    }

    #[Route('api/services/{id}', name: 'update_service', methods: 'PUT')]
    public function update(Request $request, $id): JsonResponse
    {
        return $this->controller->update($this->servicesRepository,$request, $id, Services::class);
    }

    #[Route('api/services/{id}', name: 'delete_service', methods: 'DELETE')]
    public function delete(Request $request, $id): JsonResponse
    {
        return $this->controller->delete($this->servicesRepository, $id);
    }
}
