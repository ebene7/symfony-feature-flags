<?php

namespace E7\FeatureFlagsBundle\Tests\Context;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Context\ContextInterface;
use E7\FeatureFlagsBundle\Context\Key;
use E7\FeatureFlagsBundle\Context\Provider\ProviderInterface;
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
        $this->assertInstanceOf(ContextInterface::class, new Context());
    }

    public function testConstructorPassParameters()
    {
        $key1 = 'key-' . rand(0, 9999);
        $key2 = 'key-' . rand(0, 9999);
        $key3 = 'key-' . rand(0, 9999);
        $key4 = 'key-' . rand(0, 9999);

        $provider1 = $this->createTestProvider([$key1, $key2]);
        $provider2 = $this->createTestProvider([$key3, $key4]);

        $context = new Context([$provider1, $provider2]);

        $this->assertTrue($context->has($key1));
        $this->assertTrue($context->has($key2));
        $this->assertTrue($context->has($key3));
        $this->assertTrue($context->has($key4));
    }

    public function testAddProvider()
    {
        $keys = [
            'key-' . rand(0, 9999),
            'key-' . rand(0, 9999),
            'key-' . rand(0, 9999),
        ];

        $provider = $this->createTestProvider($keys);
        $context = new Context();

        foreach ($keys as $key) {
            $this->assertFalse($context->has($key));
        }

        $this->assertSame($context, $context->addProvider($provider));

        foreach ($keys as $key) {
            $this->assertTrue($context->has($key));
        }
    }

    public function testRemoveProvider()
    {
        $key1 = 'key-' . rand(0, 9999);
        $key2 = 'key-' . rand(0, 9999);
        $key3 = 'key-' . rand(0, 9999);
        $key4 = 'key-' . rand(0, 9999);

        $provider1 = $this->createTestProvider([$key1, $key2]);
        $provider2 = $this->createTestProvider([$key3, $key4]);
        $context = new Context([$provider1, $provider2]);

        $this->assertTrue($context->has($key1));
        $this->assertTrue($context->has($key2));
        $this->assertTrue($context->has($key3));
        $this->assertTrue($context->has($key4));

        // expected to remove all claimed keys
        $this->assertSame($context, $context->removeProvider($provider1));

        $this->assertFalse($context->has($key1));
        $this->assertFalse($context->has($key2));
        $this->assertTrue($context->has($key3));
        $this->assertTrue($context->has($key4));
    }

    public function testSetData()
    {
        $key = 'key-' . rand(0, 9999);
        $value = 'value-' . rand(0, 9999);
        $context = new Context();

        $this->assertFalse($context->has($key));
        $this->assertSame($context, $context->setData([$key => $value]));
        $this->assertTrue($context->has($key));
        $this->assertEquals($value, $context->get($key));
    }

    public function testFreshContextKeyValueWithDefaultValue()
    {
        $key = 'key-' . rand(0, 9999);
        $defaultValue = 'value-' . rand(0, 9999);

        $context = new Context();

        $this->assertFalse($context->has($key));
        $this->assertNull($context->get($key));
        $this->assertEquals($defaultValue, $context->get($key, $defaultValue));
    }

    public function testGetAndSet()
    {
        $key = 'key-' . rand(0, 9999);
        $value = 'value-' . rand(0, 9999);
        $context = new Context();

        $this->assertTrue(method_exists($context, 'set'));
        $this->assertTrue(method_exists($context, 'get'));
        $this->assertSame($context, $context->set($key, $value));
        $this->assertEquals($value, $context->get($key));
    }

    public function testGetAndDefault()
    {
        $key = 'key-' . rand(0, 9999);
        $defaultValue = 'value-' . rand(0, 9999);
        $context = new Context();

        $this->assertFalse($context->has($key));
        $this->assertEquals($defaultValue, $context->get($key, $defaultValue));
    }
    
//    public function testSetAndGetWithProvider()
//    {
//        $request = new \Symfony\Component\HttpFoundation\Request();
//        $provider = new \E7\FeatureFlagsBundle\Context\Provider\RequestProvider($request);
//
//        $context = new Context();
//        $context->addProvider($provider);
//        
////       echo "#". print_r($context->get('request.client_ip', '111.222.333.444'), true) . "#";
//        
//        $this->assertTrue(true);
//    }

    public function testHas()
    {
        $key = 'key-' . rand(0, 9999);
        $value = 'value-' . rand(0, 9999);
        $context = new Context([]);

        $this->assertFalse($context->has($key));
        $context->set($key, $value);
        $this->assertTrue($context->has($key));
    }

    public function testRemove()
    {
        $key = 'key-' . rand(0, 9999);
        $value = 'value-' . rand(0, 9999);
        $context = new Context([]);

        $this->assertFalse($context->has($key));
        $context->set($key, $value);
        $this->assertTrue($context->has($key));

        $this->assertSame($context, $context->remove($key));
        $this->assertFalse($context->has($key));
    }

    public function testRemoveWithKeyAndProvider()
    {
        $key1 = 'key-' . rand(0, 9999);
        $key2 = 'key-' . rand(0, 9999);
        $key3 = 'key-' . rand(0, 9999);
        $key4 = 'key-' . rand(0, 9999);
        
        $provider1 = $this->createTestProvider([$key1, $key2]);
        $provider2 = $this->createTestProvider([$key3, $key4]);
        $context = new Context([$provider1, $provider2]);
        
        $this->assertTrue($context->has($key1));
        $this->assertTrue($context->has($key2));
        $this->assertTrue($context->has($key3));
        $this->assertTrue($context->has($key4));
        
        $context->remove($key1);  // expected to remove all claimed keys
        
        $this->assertFalse($context->has($key1));
        $this->assertFalse($context->has($key2));
        $this->assertTrue($context->has($key3));
        $this->assertTrue($context->has($key4));
    }
    
    public function testRemoveMulti()
    {
        $key1 = 'key-' . rand(0, 9999);
        $key2 = 'key-' . rand(0, 9999);
        $value1 = 'value-' . rand(0, 9999);
        $value2 = 'value-' . rand(0, 9999);

        $context = new Context();

        $this->assertFalse($context->has($key1));
        $this->assertFalse($context->has($key2));
        $context->set($key1, $value1);
        $context->set($key2, $value2);
        $this->assertTrue($context->has($key1));
        $this->assertTrue($context->has($key1));

        $this->assertSame($context, $context->remove($key1, $key2));
        $this->assertFalse($context->has($key1));
        $this->assertFalse($context->has($key2));
    }
    
    /**
     * createTestProvider
     * 
     * @param array $claimedKeys
     * @param \E7\FeatureFlagsBundle\Tests\Context\callable $getCallback
     * @return ProviderInterface
     */
    protected function createTestProvider(array $claimedKeys = [], callable $getCallback = null)
    {
        return new class($claimedKeys, $getCallback) implements ProviderInterface {
            private $claimedKeys;
            private $getCallback;
            
            public function __construct(
                array $claimedKeys,
                $getCallback 
            ) {
                $this->claimedKeys = $claimedKeys;
                $this->getCallback = $getCallback ?: function() {};
            }
            
            public function get(Key $key, $default = null)
            {
                return call_user_func($this->getCallback, $key, $default);
            }

            public function getClaimedKeys(): array 
            {
                return $this->claimedKeys;
            }
        };    
    }
}
