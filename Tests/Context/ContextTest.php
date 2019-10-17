<?php

namespace E7\FeatureFlagsBundle\Tests\Context;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Context\ContextInterface;
use E7\PHPUnit\Traits\OopTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextTest
 * @package E7\FeatureFlagsBundle\Tests\Context
 */
class ContextTest extends TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf(ContextInterface::class, new Context([]));
    }

    public function testConstructorPassParameters()
    {
        $key = 'key-' . rand(0, 9999);
        $value = 'value-' . rand(0, 9999);

        $context = new Context([ $key => $value ]);

        $this->assertTrue($context->has($key));
        $this->assertEquals($value, $context->get($key));
    }

    public function testFreshContextKeyValueWithDefaultValue()
    {
        $key = 'key-' . rand(0, 9999);
        $defaultValue = 'value-' . rand(0, 9999);

        $context = new Context([]);

        $this->assertFalse($context->has($key));
        $this->assertNull($context->get($key));
        $this->assertEquals($defaultValue, $context->get($key, $defaultValue));
    }

    public function testGetAndSet()
    {
        $key = 'key-' . rand(0, 9999);
        $value = 'value-' . rand(0, 9999);
        $context = new Context([]);

        $this->assertTrue(method_exists($context, 'set'));
        $this->assertTrue(method_exists($context, 'get'));
        $this->assertSame($context, $context->set($key, $value));
        $this->assertEquals($value, $context->get($key));

    }
}
