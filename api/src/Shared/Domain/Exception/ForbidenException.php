<?php

namespace App\Shared\Domain\Exception;


class ForbidenException extends DomainExeption 
{
    public function __construct(string $message = 'Forbiden')
    {
        parent::__construct($message, 403);
    }
}