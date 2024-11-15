<?php

namespace App\Shared\Domain\UuidGenerator;

interface UuidGeneratorInterface
{
    static function generate(): string;
}