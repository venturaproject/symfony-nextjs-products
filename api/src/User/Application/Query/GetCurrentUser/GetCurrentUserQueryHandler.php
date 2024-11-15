<?php

namespace App\User\Application\Query\GetCurrentUser;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Security\CurrentUserProviderInterface;
use App\User\Infrastructure\DTO\UserDTO;
use App\User\Domain\Exception\UserNotFoundException;

class GetCurrentUserQueryHandler implements QueryHandlerInterface
{
    private CurrentUserProviderInterface $currentUserProvider;

    public function __construct(CurrentUserProviderInterface $currentUserProvider)
    {
        $this->currentUserProvider = $currentUserProvider;
    }

    public function __invoke(GetCurrentUserQuery $query): UserDTO
    {
        $user = $this->currentUserProvider->getRequiredCurrentUser();
        
        // Debug: Verifica el usuario actual
        dump($user); // Asegúrate de que esto se muestra en el panel de depuración de Symfony.
    
        return new UserDTO($user->getId(), $user->getUsername(), $user->getEmail());
    }
}