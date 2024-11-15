<?php

namespace App\User\Infrastructure\Controller\Api;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Application\Command\DeleteUser\DeleteUserCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users')]
#[Route('/api/users/{id}', name: 'api_users_delete', methods: ['DELETE'], requirements: ['id' => Requirement::UUID_V4])]
class DeleteUserController extends AbstractController
{
    public function __construct(private CommandBusInterface $commandBus) { }

    public function __invoke(string $id): JsonResponse
    {
        $command = new DeleteUserCommand(
            new Uuid($id)
        );

        $this->commandBus->execute($command);

        return $this->json([], 204);
    }
}