<?php

use Challenge02\Contracts\Auth\AuthInterface;
use Challenge02\Contracts\Auth\TokenInterface;
use Challenge02\Contracts\Auth\PasswordHasherInterface;
use Challenge02\Contracts\Repositories\UserRepositoryInterface;
use Challenge02\Contracts\Services\MailServiceInterface;

use Challenge02\Infrastructure\Persistence\PdoUserRepository;
use Challenge02\Infrastructure\Security\JwtTokenManager;
use Challenge02\Infrastructure\Security\BcryptPasswordHasher;
use Challenge02\Infrastructure\Security\AuthManager;
use Challenge02\Infrastructure\Session\PhpSessionManager;
use Challenge02\Infrastructure\Mail\LegacyMailService;

use Challenge02\Core\Auth\RegisterUserUseCase;
use Challenge02\Core\Auth\LoginUserUseCase;
use Challenge02\Core\Auth\LogoutUserUseCase;
use Challenge02\Core\Auth\ResetPasswordUseCase;
use Challenge02\Core\Auth\GenerateTokenUseCase;
use Challenge02\Core\User\GetUserProfileUseCase;

use Challenge02\Application\AuthService;
use Challenge02\Application\UserService;

use Challenge02\Http\Controllers\AuthController;
use Challenge02\Http\Controllers\UserController;

use PDO;

// Database connection
$dbHost = $_ENV['DB_HOST'] ?? 'localhost';
$dbName = $_ENV['DB_NAME'] ?? 'challenge_02_auth';
$dbUser = $_ENV['DB_USER'] ?? 'root';
$dbPass = $_ENV['DB_PASS'] ?? '';

$pdo = new PDO(
    "mysql:host={$dbHost};dbname={$dbName}",
    $dbUser,
    $dbPass
);

// Infrastructure implementations
$userRepository = new PdoUserRepository($pdo);
$passwordHasher = new BcryptPasswordHasher($_ENV['PASSWORD_COST'] ?? 12);
$jwtSecret = $_ENV['JWT_SECRET'] ?? 'default_secret';
$tokenManager = new JwtTokenManager($jwtSecret);
$sessionManager = new PhpSessionManager();
$mailService = new LegacyMailService();
$authManager = new AuthManager($tokenManager, $userRepository);

// Core use cases
$registerUserUseCase = new RegisterUserUseCase($userRepository, $passwordHasher, $mailService);
$loginUserUseCase = new LoginUserUseCase($userRepository, $passwordHasher, $tokenManager);
$logoutUserUseCase = new LogoutUserUseCase($tokenManager);
$resetPasswordUseCase = new ResetPasswordUseCase($userRepository, $passwordHasher, $mailService);
$generateTokenUseCase = new GenerateTokenUseCase($tokenManager);
$getUserProfileUseCase = new GetUserProfileUseCase($userRepository);

// Application services
$authService = new AuthService(
    $registerUserUseCase,
    $loginUserUseCase,
    $logoutUserUseCase,
    $resetPasswordUseCase,
    $generateTokenUseCase
);

$userService = new UserService($getUserProfileUseCase);

// Controllers
$authController = new AuthController($authService);
$userController = new UserController($userService);

// Return container array
return [
    // Infrastructure
    UserRepositoryInterface::class => $userRepository,
    PasswordHasherInterface::class => $passwordHasher,
    TokenInterface::class => $tokenManager,
    AuthInterface::class => $authManager,
    MailServiceInterface::class => $mailService,
    
    // Core
    RegisterUserUseCase::class => $registerUserUseCase,
    LoginUserUseCase::class => $loginUserUseCase,
    LogoutUserUseCase::class => $logoutUserUseCase,
    ResetPasswordUseCase::class => $resetPasswordUseCase,
    GenerateTokenUseCase::class => $generateTokenUseCase,
    GetUserProfileUseCase::class => $getUserProfileUseCase,
    
    // Application
    AuthService::class => $authService,
    UserService::class => $userService,
    
    // Http
    AuthController::class => $authController,
    UserController::class => $userController,
];