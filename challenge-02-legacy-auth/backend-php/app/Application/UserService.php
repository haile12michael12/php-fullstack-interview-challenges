<?php

namespace Challenge02\Application;

use Challenge02\Core\User\GetUserProfileUseCase;

class UserService
{
    private GetUserProfileUseCase $getUserProfileUseCase;

    public function __construct(GetUserProfileUseCase $getUserProfileUseCase)
    {
        $this->getUserProfileUseCase = $getUserProfileUseCase;
    }

    public function getProfile(string $userId): array
    {
        return $this->getUserProfileUseCase->execute($userId);
    }
}