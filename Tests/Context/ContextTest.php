<?php

namespace E7\FeatureFlagsBundle\Tests\Context;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Context\ContextInterface;
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
}
