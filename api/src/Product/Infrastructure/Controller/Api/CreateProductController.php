<?php

namespace App\Product\Infrastructure\Controller\Api;

use App\Product\Application\Command\CreateProduct\CreateProductCommand;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Infrastructure\ValidationDTO\CreateProductInputDTO;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

#[OA\Tag(name: 'Products')]
#[Route('/api/products', name: 'api_products_create', methods: ['POST'])]
class CreateProductController extends AbstractController
{
    public function __construct(private CommandBusInterface $commandBus) { }

    public function __invoke(#[MapRequestPayload] CreateProductInputDTO $dto): JsonResponse
    {
      
        $dateAdd = null;
        if ($dto->date_add) {
            $dateAdd = \DateTime::createFromFormat('Y-m-d', $dto->date_add);
            if (!$dateAdd) {
                throw new \InvalidArgumentException("Invalid date format for date_add.");
            }
        }

        $command = new CreateProductCommand(
            $dto->name,
            $dto->price,
            $dto->description,
            $dateAdd 
        );

        /** @var ProductDTO $product */
        $product = $this->commandBus->execute($command);

        return $this->json($product, Response::HTTP_CREATED);
    }
}


