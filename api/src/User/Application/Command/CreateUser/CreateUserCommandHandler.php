<?php

namespace App\User\Application\Command\CreateUser;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\User\Domain\Factory\UserFactory;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Entity\User;

class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UserFactory $userFactory
    ) { }

    /**
     * @return User The created user.
     */
    public function __invoke(CreateUserCommand $command): User
    {
        $user = $this->userFactory->create(
            $command->userName, 
            $command->email, 
            $command->password
        );

        $this->repository->save($user);

        return $user;
    }
}
