<?php

namespace Tests\Unit;

use App\Handler\ExceptionHandler;
use App\Exception\ApplicationException;
use App\Exception\ValidationException;
use App\Exception\DatabaseException;
use App\Exception\AuthenticationException;
use App\Exception\AuthorizationException;
use App\Exception\ExternalServiceException;
use PHPUnit\Framework\TestCase;
use Exception;

class ExceptionHandlerTest extends TestCase
{
    private ExceptionHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new ExceptionHandler();
    }

    public function testHandleApplicationException()
    {
        $exception = new ApplicationException('Test exception', 500);
        $response = $this->handler->handle($exception);
        
        $this->assertIsArray($response);
        $this->assertEquals(500, $response['status_code']);
        $this->assertArrayHasKey('body', $response);
        
        $body = json_decode($response['body'], true);
        $this->assertArrayHasKey('message', $body);
        $this->assertEquals('Test exception', $body['message']);
    }

    public function testHandleValidationException()
    {
        $errors = ['field' => 'Field is required'];
        $exception = new ValidationException('Validation failed', $errors);
        $response = $this->handler->handle($exception);
        
        $this->assertIsArray($response);
        $this->assertEquals(422, $response['status_code']);
        
        $body = json_decode($response['body'], true);
        $this->assertArrayHasKey('errors', $body);
        $this->assertEquals($errors, $body['errors']);
    }

    public function testHandleDatabaseException()
    {
        $exception = new DatabaseException('Database error', 500);
        $response = $this->handler->handle($exception);
        
        $this->assertIsArray($response);
        $this->assertEquals(500, $response['status_code']);
    }

    public function testHandleAuthenticationException()
    {
        $exception = new AuthenticationException('Auth failed', 401);
        $response = $this->handler->handle($exception);
        
        $this->assertIsArray($response);
        $this->assertEquals(401, $response['status_code']);
    }

    public function testHandleAuthorizationException()
    {
        $exception = new AuthorizationException('Access denied', 403);
        $response = $this->handler->handle($exception);
        
        $this->assertIsArray($response);
        $this->assertEquals(403, $response['status_code']);
    }

    public function testHandleExternalServiceException()
    {
        $exception = new ExternalServiceException('Service error', 502);
        $response = $this->handler->handle($exception);
        
        $this->assertIsArray($response);
        $this->assertEquals(502, $response['status_code']);
    }

    public function testHandleGenericException()
    {
        $exception = new Exception('Generic error');
        $response = $this->handler->handle($exception);
        
        $this->assertIsArray($response);
        $this->assertEquals(500, $response['status_code']);
        
        $body = json_decode($response['body'], true);
        $this->assertArrayHasKey('message', $body);
        $this->assertEquals('An unexpected error occurred', $body['message']);
    }
}