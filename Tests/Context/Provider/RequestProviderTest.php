<?php

namespace E7\FeatureFlagsBundle\Tests\Context\Provider;

use E7\FeatureFlagsBundle\Context\Provider\RequestProvider;
use E7\FeatureFlagsBundle\Context\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class RequestProviderTest
 * @package E7\FeatureFlagsBundle\Tests\Context
 */
class RequestProviderTest extends TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf(ProviderInterface::class, new RequestProvider([]));
    }
}