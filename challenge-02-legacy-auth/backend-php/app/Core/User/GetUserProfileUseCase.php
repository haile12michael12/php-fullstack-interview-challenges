<?php

namespace Challenge02\Core\User;

use Challenge02\Contracts\Repositories\UserRepositoryInterface;
use Challenge02\Domain\User;

class GetUserProfileUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $userId): array
    {
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            throw new \Exception('User not found');
        }

        return $user->toArray();
    }
}