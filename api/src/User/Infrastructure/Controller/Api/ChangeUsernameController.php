<?php

namespace App\User\Infrastructure\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\ChangeUsername\ChangeUsernameCommand;
use App\User\Infrastructure\DTO\UpdateUsernameDTO;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users')]
#[Route('/api/users/username', name: 'api_users_change_username', methods: ['PUT'])]
class ChangeUsernameController extends AbstractController
{
    public function __construct(private CommandBusInterface $commandBus) { }

    public function __invoke(#[MapRequestPayload] UpdateUsernameDTO $updateUsernameDTO): JsonResponse
    {
        $command = new ChangeUsernameCommand($updateUsernameDTO->username);
        $this->commandBus->execute($command);

        return $this->json(null, 204);
    }
}
