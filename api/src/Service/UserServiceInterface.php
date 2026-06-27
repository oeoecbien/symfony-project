<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Lcobucci\JWT\Token;

interface UserServiceInterface
{
    public function getToken(User $user): string;

    public function parseToken(string $token): ?Token;

    public function findOneByEmail(string $token): string|false;
}
