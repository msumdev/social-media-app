<?php

namespace App\Services\Authentication;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

/**
 * Class JWTService
 */
class JWTService
{
    public function generate(): array
    {
        $config = Configuration::forSymmetricSigner(
            new Sha256,
            InMemory::plainText(base64_decode(env('JWT_SECRET')))
        );

        $now = new \DateTimeImmutable;
        $expiresAt = $now->modify('+1 year');

        $userId = auth()->id();
        $ipAddress = request()->ip();

        $token = $config->builder()
            ->issuedAt($now)
            ->issuedBy(env('APP_URL'))
            ->permittedFor(env('APP_URL'))
            ->expiresAt($expiresAt)
            ->identifiedBy(uniqid(), true)
            ->withClaim('userId', $userId)
            ->withClaim('ip', $ipAddress)
            ->getToken($config->signer(), $config->signingKey());

        return [
            'token' => $token->toString(),
            'expires_at' => $expiresAt,
        ];
    }
}
