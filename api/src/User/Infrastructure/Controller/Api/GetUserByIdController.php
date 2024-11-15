<?php

namespace App\User\Infrastructure\Controller\Api;

use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Application\Query\GetUserById\GetUserByIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users')]
#[Route('/api/users/{id}', name: 'api_users_get_by_id', methods: ['GET'], requirements: ['id' => Requirement::UUID_V4])]
class GetUserByIdController extends AbstractController
{
    public function __construct(private QueryBusInterface $queryBus) { }

    public function __invoke(string $id): JsonResponse
    {
        $message = new GetUserByIdQuery(new Uuid($id));
        $user = $this->queryBus->execute($message);
        
        return $this->json($user, 200, [], ['groups' => ['user:read']]);
    }
}