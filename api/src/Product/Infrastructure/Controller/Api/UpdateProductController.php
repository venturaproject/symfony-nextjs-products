<?php

namespace App\Product\Infrastructure\Controller\Api;

use App\Product\Application\Command\UpdateProduct\UpdateProductCommand;
use App\Product\Infrastructure\ValidationDTO\UpdateProductDTO;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use App\Product\Domain\Exception\ProductNotFoundException;

#[OA\Tag(name: 'Products')]
#[Route('/api/products/{id}', name: 'api_products_update', methods: ['PUT', 'PATCH'], requirements: ['id' => Requirement::UUID_V4])]
class UpdateProductController extends AbstractController
{
    public function __construct(private CommandBusInterface $commandBus) { }

    public function __invoke(#[MapRequestPayload] UpdateProductDTO $updateProductDTO, string $id): JsonResponse
    {
        $command = new UpdateProductCommand(
            new Uuid($id),
            $updateProductDTO->name,
            $updateProductDTO->price,
            $updateProductDTO->description,
            $updateProductDTO->date_add
        );

        try {
            $this->commandBus->execute($command);
            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (ProductNotFoundException $e) {
            return $this->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    }
}
