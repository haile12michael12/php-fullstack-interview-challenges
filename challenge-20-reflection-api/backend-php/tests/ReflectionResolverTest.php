<?php

namespace Tests;

use App\Container\Core\Container;
use App\Container\Core\ReflectionResolver;
use App\Container\Exception\ContainerException;
use PHPUnit\Framework\TestCase;

class ReflectionResolverTest extends TestCase
{
    private Container $container;
    private ReflectionResolver $resolver;

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->resolver = new ReflectionResolver($this->container);
    }

    public function testResolveClassWithoutConstructor(): void
    {
        $instance = $this->resolver->resolve(\stdClass::class);
        $this->assertInstanceOf(\stdClass::class, $instance);
    }

    public function testResolveClassWithDependencies(): void
    {
        // Register a dependency in the container
        $this->container->register(\stdClass::class, function () {
            return new \stdClass();
        });

        // Create a test class that depends on stdClass
        $testClass = new class(new \stdClass()) {
            public function __construct(private \stdClass $dependency)
            {
            }

            public function getDependency(): \stdClass
            {
                return $this->dependency;
            }
        };

        // Register the test class
        $this->container->register('test_class', function () use ($testClass) {
            return $testClass;
        });

        // The resolver should be able to create an instance
        $resolved = $this->resolver->resolve(get_class($testClass));
        $this->assertInstanceOf(get_class($testClass), $resolved);
        $this->assertInstanceOf(\stdClass::class, $resolved->getDependency());
    }

    public function testResolveParameterWithDefaultValue(): void
    {
        $testClass = new class('default') {
            public function __construct(private string $value = 'default')
            {
            }

            public function getValue(): string
            {
                return $this->value;
            }
        };

        $resolved = $this->resolver->resolve(get_class($testClass));
        $this->assertInstanceOf(get_class($testClass), $resolved);
        $this->assertEquals('default', $resolved->getValue());
    }

    public function testResolveFailsWithUnresolvableParameter(): void
    {
        $this->expectException(ContainerException::class);

        $testClass = new class(new \DateTime()) {
            public function __construct(private \DateTime $dateTime)
            {
            }
        };

        // DateTime is not registered in the container and cannot be resolved automatically
        $this->resolver->resolve(get_class($testClass));
    }
}