<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        #[Autowire('%kernel.secret%')]
        private readonly string $appSecret,
        #[Autowire('%env(DEFAULT_URI)%')]
        private readonly string $apiUri,
    ) {
    }

    public function getToken(User $user): string
    {
        $tokenBuilder = new Builder(new JoseEncoder(), ChainedFormatter::default());
        $algorithm = new Sha256();
        $signingKey = InMemory::plainText($this->appSecret);
        $now = new DateTimeImmutable();
        $token = $tokenBuilder
            ->issuedBy($this->apiUri)
            ->permittedFor($this->apiUri)
            ->identifiedBy(hash('sha1', (string) $user->getEmail()))
            ->issuedAt($now)
            ->expiresAt($now->modify('+2 hours'))
            ->withClaim('uid', $user->getId())
            ->withClaim('email', $user->getEmail())
            ->getToken($algorithm, $signingKey);

        return $token->toString();
    }

    public function parseToken(string $token): ?Token
    {
        $parser = new Parser(new JoseEncoder());

        try {
            return $parser->parse($token);
        } catch (CannotDecodeContent|InvalidTokenStructure|UnsupportedHeaderFound) {
            return null;
        }
    }

    public function findOneByEmail(string $token): string|false
    {
        $tokenParse = $this->parseToken($token);
        if ($tokenParse === null) {
            return false;
        }

        $email = $tokenParse->claims()->get('email');
        if (!is_string($email) || $email === '') {
            return false;
        }

        $user = $this->userRepository->findOneByEmail($email);

        return $user instanceof User ? (string) $user->getEmail() : false;
    }
}
