<?php

namespace PhpHelpers\Services;

use PhpHelpers\DTOs\AuthResponseDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

class AuthService
{
    private array $keysResponse = ['id', 'name', 'email', 'avatar'];

    /**
     * Set the keys to be returned in the response for the user.
     *
     * @param array $keys
     * @return $this
     */
    public function setKeyResponse(array $keys): self
    {
        $this->keysResponse = $keys;
        return $this;
    }

    /**
     * Attempt to login with the given credentials.
     *
     * @param string $email
     * @param string $password
     * @return AuthResponseDTO
     * @throws \Exception
     */
    public function attemptLogin(string $email, string $password): AuthResponseDTO
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password]))
            throw new \Exception('Wrong Credentials', 401);

        $user = Auth::user();
        if (!($user instanceof User))
            throw new \Exception('Bad request', 422);

        $user->tokens()->delete();
        $token = $this->createToken($user);

        return new AuthResponseDTO($user, $token->plainTextToken, $this->keysResponse);
    }

    /**
     * Create a new personal access token for the given user.
     *
     * @param \App\Models\User $user
     * @param int $addHours
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(User $user, int $addHours = 24): NewAccessToken
    {
        return $user->createToken('api-token', ['*'], now()->addHours($addHours));
    }
}
