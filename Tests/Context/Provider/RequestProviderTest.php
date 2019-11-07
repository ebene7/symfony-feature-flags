<?php

namespace E7\FeatureFlagsBundle\Tests\Context\Provider;

use E7\FeatureFlagsBundle\Context\Provider\RequestProvider;
use E7\FeatureFlagsBundle\Context\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestProviderTest
 * @package E7\FeatureFlagsBundle\Tests\Context
 */
class RequestProviderTest extends TestCase
{
    public function testInstanceOf()
    {
        $this->assertInstanceOf(ProviderInterface::class, new RequestProvider(new Request()));
    }

    public function testGetClaimedKeys()
    {
        $provider = new RequestProvider(new Request());

        $this->assertInternalType('array', $provider->getClaimedKeys());
        $this->assertCount(1, $provider->getClaimedKeys());
        $this->assertContains('request', $provider->getClaimedKeys());
    }
}
