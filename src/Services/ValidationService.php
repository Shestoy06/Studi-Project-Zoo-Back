<?php
// src/Service/ValidationService.php
namespace App\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateEntity($entity): ?JsonResponse
    {
        $errors = $this->validator->validate($entity);

        if (count($errors) > 0) {
            $errorMessages = [];

            foreach ($errors as $error) {
                $propertyPath = $error->getPropertyPath();
                $errorMessages[$propertyPath][] = $error->getMessage();
            }

            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        return null; // No validation errors
    }
}
