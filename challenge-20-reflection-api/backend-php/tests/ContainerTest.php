<?php

namespace Tests;

use App\Container\Core\Container;
use App\Container\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testRegisterAndGetService(): void
    {
        $this->container->register('test', function () {
            return new \stdClass();
        });

        $this->assertTrue($this->container->has('test'));
        $this->assertInstanceOf(\stdClass::class, $this->container->get('test'));
    }

    public function testGetNonExistentServiceThrowsException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->container->get('nonexistent');
    }

    public function testSameInstanceIsReturned(): void
    {
        $this->container->register('test', function () {
            return new \stdClass();
        });

        $instance1 = $this->container->get('test');
        $instance2 = $this->container->get('test');

        $this->assertSame($instance1, $instance2);
    }

    public function testRegisterProvider(): void
    {
        $provider = new class {
            public function register($container): void
            {
                $container->register('provider_test', function () {
                    return 'provider_value';
                });
            }
        };

        $this->container->registerProvider($provider);
        $this->assertTrue($this->container->has('provider_test'));
        $this->assertEquals('provider_value', $this->container->get('provider_test'));
    }
}