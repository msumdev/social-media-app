<?php

namespace App\Services\Authentication;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegistrationRequest;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Jobs\Auth\NewUser;
use App\Jobs\Auth\ResetPassword;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

/**
 * Class JWTService
 * @package App\Services\Authentication
 */
class JWTService
{
    /**
     * @return array
     */
    public function generate(): array
    {
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(base64_decode(env('JWT_SECRET')))
        );

        $now = new \DateTimeImmutable();
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
            'expires_at' => $expiresAt
        ];
    }
}
