<?php

namespace App\Product\Domain\Service;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Domain\Exception\ForbidenException;
use App\Shared\Domain\Security\CurrentUserProviderInterface;
use App\Product\Domain\Exception\ProductNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;

class DeleteProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CurrentUserProviderInterface $currentUserProvider
    ) { }

    public function delete(Uuid $id): void
    {
     
        $currentUser = $this->currentUserProvider->getRequiredCurrentUser();
        
        if (in_array('ROLE_ADMIN', $currentUser->getRoles()) === false) {
            throw new ForbidenException(); 
        }

        $product = $this->productRepository->findById($id);

        if ($product === null) {
            throw new ProductNotFoundException(); 
        }

        $this->productRepository->remove($product);
    }
}

