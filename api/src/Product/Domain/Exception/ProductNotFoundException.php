<?php

namespace App\Product\Domain\Exception;

use RuntimeException;

class ProductNotFoundException extends RuntimeException
{
    public function __construct(string $message = "Producto no encontrado", int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
