<?php

namespace App\User\Infrastructure\Controller\Api;

use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\CreateUser\CreateUserCommand;
use App\User\Infrastructure\DTO\CreateUserInputDTO;
use App\User\Domain\Entity\User; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users')]
#[Route('/api/users/create', name: 'api_users_create', methods: ['POST'])]
class CreateUserController extends AbstractController
{
    public function __construct(private CommandBusInterface $commandBus) { }

    public function __invoke(#[MapRequestPayload] CreateUserInputDTO $userData): JsonResponse
    {
        $user = new CreateUserCommand(
            $userData->username,
            $userData->email,
            $userData->password
        );

          /** @var User $userCreated */
        $userCreated = $this->commandBus->execute($user);

        return $this->json($userCreated, Response::HTTP_CREATED, [], ['groups' => ['user:read']]);
    }
}