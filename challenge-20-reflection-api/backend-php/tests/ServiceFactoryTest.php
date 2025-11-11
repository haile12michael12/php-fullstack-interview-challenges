<?php

namespace Tests;

use App\Container\Core\Container;
use App\Container\Core\ReflectionResolver;
use App\Container\Core\ServiceFactory;
use App\Container\Exception\CircularDependencyException;
use PHPUnit\Framework\TestCase;

class ServiceFactoryTest extends TestCase
{
    private Container $container;
    private ReflectionResolver $resolver;
    private ServiceFactory $factory;

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->resolver = new ReflectionResolver($this->container);
        $this->factory = new ServiceFactory($this->resolver);
    }

    public function testCreateInstance(): void
    {
        $instance = $this->factory->create(\stdClass::class);
        $this->assertInstanceOf(\stdClass::class, $instance);
    }

    public function testCreateWithParameters(): void
    {
        $testClass = new class('test') {
            public function __construct(private string $value)
            {
            }

            public function getValue(): string
            {
                return $this->value;
            }
        };

        $instance = $this->factory->createWithParameters(get_class($testClass), ['parameter_value']);
        $this->assertInstanceOf(get_class($testClass), $instance);
        $this->assertEquals('parameter_value', $instance->getValue());
    }

    public function testGetReflection(): void
    {
        $reflection = $this->factory->getReflection(\stdClass::class);
        $this->assertInstanceOf(\ReflectionClass::class, $reflection);
        $this->assertEquals(\stdClass::class, $reflection->getName());
    }

    public function testCircularDependencyDetection(): void
    {
        $this->expectException(CircularDependencyException::class);

        // Create classes with circular dependencies
        $classA = new class() {
            public function __construct(ClassB $b) {}
        };

        $classB = new class() {
            public function __construct(ClassA $a) {}
        };

        // This should throw a CircularDependencyException
        $this->factory->create(get_class($classA));
    }

    public function testGetResolver(): void
    {
        $this->assertSame($this->resolver, $this->factory->getResolver());
    }
}