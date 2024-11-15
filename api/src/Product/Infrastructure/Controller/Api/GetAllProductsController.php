<?php

namespace App\Product\Infrastructure\Controller\Api;

use App\Product\Application\Query\GetAll\GetAllProductsQuery;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Products')]
#[Route('/api/products', name: 'api_products_get_all', methods: ['GET'])]
class GetAllProductsController extends AbstractController
{
    public function __construct(private QueryBusInterface $queryBusInterface) { }

    public function __invoke(): JsonResponse
    {
        // Crea una instancia de la consulta
        $query = new GetAllProductsQuery();

        // Ejecuta la consulta a través del QueryBus
        $products = $this->queryBusInterface->execute($query);

        // Retorna los productos en formato JSON
        return $this->json($products, 200);
    }
}
