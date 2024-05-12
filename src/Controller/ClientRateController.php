<?php

namespace App\Controller;

use App\Entity\ClientRate;
use App\Entity\Services;
use App\Repository\ClientRateRepository;
use App\Repository\ServicesRepository;
use App\Services\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ClientRateController extends AbstractController
{
    public function __construct(
        private ClientRateRepository $clientRateRepository,
        private readonly SuperCrudController $controller,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly ValidationService   $validationService,
    ){}
    #[Route('api/rates', name: 'get_rates', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        return $this->controller->getAll($this->clientRateRepository);
    }

    #[Route('api/rates/{id}', name: 'get_rate', methods: 'GET')]
    public function get($id): JsonResponse
    {
        return $this->controller->get($this->clientRateRepository, $id);
    }

    #[Route('api/rates', name: 'post_rate', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        return $this->controller->create($request, ClientRate::class);
    }

    #[Route('api/rates/{id}', name: 'update_rate', methods: 'PUT')]
    public function update(Request $request, $id): JsonResponse
    {
        return $this->controller->update($this->clientRateRepository,$request, $id, ClientRate::class);
    }

    #[Route('api/rates/{id}', name: 'delete_rate', methods: 'DELETE')]
    public function delete($id): JsonResponse
    {
        return $this->controller->delete($this->clientRateRepository, $id);
    }
}
