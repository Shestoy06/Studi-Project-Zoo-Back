<?php

namespace App\Controller;

use App\Entity\Services;
use App\Entity\User;
use App\Repository\ServicesRepository;
use App\Repository\UserRepository;
use App\Services\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private readonly SuperCrudController $controller,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly ValidationService   $validationService,
    ){}
    #[Route('api/user', name: 'get_users', methods: 'GET')]
    public function getAll(): JsonResponse
    {
        return $this->controller->getAll($this->userRepository);
    }

    #[Route('api/user/find', name: 'get_user', methods: 'POST')]
    public function get(Request $request): JsonResponse
    {
        $post_data = json_decode($request->getContent(), true);
        $username = $post_data['username'];
        $password = $post_data['password'];

        $user = $this->userRepository->findOneBy(['username' => $username]);

        if($user) {
            if($user->getPassword() === $password) {
                $userData = $this->serializer->normalize($user, 'json');
                return $this->json($userData);
            }
        }

        return new JsonResponse(['message' => 'User didn\'t found'], Response::HTTP_OK);

    }

    #[Route('api/user', name: 'post_user', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        return $this->controller->create($request, User::class);
    }

    #[Route('api/user/{id}', name: 'update_user', methods: 'PUT')]
    public function update(Request $request, $id): JsonResponse
    {
        return $this->controller->update($this->userRepository,$request, $id, User::class);
    }

    #[Route('api/user/{id}', name: 'delete_user', methods: 'DELETE')]
    public function delete(Request $request, $id): JsonResponse
    {
        return $this->controller->delete($this->userRepository, $id);
    }
}
