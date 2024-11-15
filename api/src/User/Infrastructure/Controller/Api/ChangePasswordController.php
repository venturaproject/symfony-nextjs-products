<?php

namespace App\User\Infrastructure\Controller\Api;

use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\ChangePassword\ChangePasswordCommand;
use App\User\Infrastructure\DTO\UpdatePasswordDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Exception;

#[OA\Tag(name: 'Users')]
#[Route('/api/users/password', name: 'api_users_change_password', methods: ['PUT'])]
class ChangePasswordController extends AbstractController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $updatePasswordDTO = new UpdatePasswordDTO(
            $data['current_password'] ?? '',
            $data['new_password'] ?? '',
            $data['new_password_confirmation'] ?? ''
        );

        // Validación de los datos de entrada
        $errors = $this->validator->validate($updatePasswordDTO);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $command = new ChangePasswordCommand(
                $updatePasswordDTO->current_password,
                $updatePasswordDTO->new_password
            );

            $this->commandBus->execute($command);

            return $this->json(['message' => 'Password changed successfully'], Response::HTTP_OK);

        } catch (BadCredentialsException $e) {
            return $this->json(['error' => 'Current password is incorrect'], Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            return $this->json(['error' => 'An error occurred while changing the password'], Response::HTTP_LOCKED);
        }
    }
}
