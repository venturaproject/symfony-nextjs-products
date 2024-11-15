<?php

namespace App\User\Domain\Entity;

use App\Shared\Domain\Aggregate\AggregateRoot;
use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\Event\UserDeletedEvent;

class User extends AggregateRoot implements AuthUserInterface
{
    private Uuid $id;
    private string $username;
    private string $email;
    private string $password;

    /**
     * @var string[] The roles assigned to the user
     */
    private array $roles = [];

    public function __construct(Uuid $id, string $username, string $email, string $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function setUserName(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }       

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return string[] The roles assigned to the user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';
        return $roles;
    }

    public function addRole(string $role): void
    {
        $this->roles[] = $role;
    }

    public function delete(): void
    {
        $this->recordEvent(new UserDeletedEvent($this->id));
    }
}
