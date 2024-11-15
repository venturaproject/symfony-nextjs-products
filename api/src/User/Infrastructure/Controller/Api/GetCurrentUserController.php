<?php

namespace App\User\Infrastructure\Controller\Api;

use App\Shared\Application\Query\QueryBusInterface;
use App\User\Application\Query\GetCurrentUser\GetCurrentUserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Users')]
#[Route('/api/users/current', name: 'api_users_get_current', methods: ['GET'])]
class GetCurrentUserController extends AbstractController
{
    public function __construct(private QueryBusInterface $queryBus) { }

    public function __invoke(): JsonResponse
    {
        // Crea una instancia de GetCurrentUserQuery
        $query = new GetCurrentUserQuery();
        
        // Ejecuta el query usando el QueryBus
        $user = $this->queryBus->execute($query);
        
        // Retorna la respuesta JSON con el DTO del usuario
        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['user:read']]);
    }
}

