<?php

namespace App\Presentation\Controller;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController
{
    private UserRepositoryInterface $userRepository;
    private Logger $logger;

    public function __construct(
        UserRepositoryInterface $userRepository,
        Logger $logger
    ) {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    public function getUser(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $userId = (int) $args['id'];
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            $response->getBody()->write(json_encode(['error' => 'User not found']));
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s')
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createUser(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        
        // Validate input
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
            $response->getBody()->write(json_encode(['error' => 'Missing required fields']));
            return $response->withStatus(400);
        }

        $user = new User(
            0, // ID will be assigned by database
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            new \DateTimeImmutable()
        );

        $this->userRepository->save($user);

        $response->getBody()->write(json_encode([
            'message' => 'User created successfully',
            'user_id' => $user->getId()
        ]));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}