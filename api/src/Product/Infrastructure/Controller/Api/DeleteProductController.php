<?php

namespace App\Product\Infrastructure\Controller\Api;

use App\Product\Application\Command\DeleteProduct\DeleteProductCommand;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Shared\Domain\Exception\ForbidenException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Products')]
#[Route('/api/products/{id}', name: 'api_products_delete', methods: ['DELETE'], requirements: ['id' => Requirement::UUID_V4])]
class DeleteProductController extends AbstractController
{
    public function __construct(private CommandBusInterface $commandBus) { }

    public function __invoke(string $id): JsonResponse
    {
        try {
            $command = new DeleteProductCommand(new Uuid($id));
            $this->commandBus->execute($command);
    
            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (ForbidenException $e) {
            return $this->json(['error' => 'Forbidden: insufficient permissions'], Response::HTTP_FORBIDDEN);
        } catch (ProductNotFoundException $e) {
            return $this->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->json(['error' => 'An unexpected error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
