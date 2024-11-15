<?php

namespace App\Product\Infrastructure\Controller\Api;

use App\Product\Application\Query\GetById\GetIdProductQuery;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Products')]
#[Route('/api/products/{id}', name: 'api_product_get_by_id', methods: ['GET'])]
class GetIdProductController extends AbstractController
{
    public function __construct(private QueryBusInterface $queryBusInterface) { }

    public function __invoke(string $id): JsonResponse
    {
        // Crea la consulta con el ID proporcionado
        $query = new GetIdProductQuery($id);

        // Ejecuta la consulta a través del QueryBus
        $product = $this->queryBusInterface->execute($query);

        // Si el producto no se encuentra, retorna un 404
        if (!$product) {
            return $this->json(['error' => 'Product not found'], 404);
        }

        // Retorna el producto en formato JSON
        return $this->json($product, 200);
    }
}
