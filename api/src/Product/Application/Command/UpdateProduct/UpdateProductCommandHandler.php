<?php

namespace App\Product\Application\Command\UpdateProduct;

use App\Product\Domain\Service\UpdateProductService;
use App\Shared\Application\Command\CommandHandlerInterface;

class UpdateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UpdateProductService $updateProductService
    ) { }

    public function __invoke(UpdateProductCommand $command): void
    {
        $this->updateProductService->update(
            $command->id,
            $command->name,
            $command->price,
            $command->description, 
            $command->date_add
        );
    }
}
