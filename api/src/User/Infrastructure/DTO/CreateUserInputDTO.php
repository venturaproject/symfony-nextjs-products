<?php

namespace App\User\Infrastructure\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateUserInputDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Please enter your email address')]
        #[Assert\Email(message: 'Please enter a valid email address')]
        public string $email,

        #[Assert\NotBlank(message: 'Please enter your password')]
        #[Assert\Length(
            min: 6, 
            minMessage: 'Your password must be at least 6 characters long'
        )]
        public string $password,
        
        #[Assert\NotBlank(message: 'please enter your username')]
        #[Assert\Length(
            min:3, 
            max: 20, 
            minMessage: 'Your username must have at least 3 characters',
            maxMessage: 'Your username must have at most 20 characters'
        )]
        public string $username,
    ){
    }
}