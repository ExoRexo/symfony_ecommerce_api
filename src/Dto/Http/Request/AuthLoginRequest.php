<?php

namespace App\Dto\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AuthLoginRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        public string $password,
    ) {
    }
}
