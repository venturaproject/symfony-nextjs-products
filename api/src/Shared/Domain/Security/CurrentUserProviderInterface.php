<?php

namespace App\Shared\Domain\Security;

interface CurrentUserProviderInterface
{
    public function getRequiredCurrentUser(): AuthUserInterface;
    public function getNullableCurrentUser(): ?AuthUserInterface;
}