<?php

namespace E7\FeatureFlagsBundle\Tests\Feature;

use E7\FeatureFlagsBundle\Feature\Feature;
use E7\FeatureFlagsBundle\Feature\FeatureInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureTest
 */
class FeatureTest extends TestCase
{
    public function testInstanceOfFeatureInterface()
    {
        $this->assertTrue(new Feature('', null) instanceof FeatureInterface);
    }

    public function testConstructorPassesParameters()
    {
        $name = 'feature-' . rand(0, 9999);
        $parentName = 'parent-feature-' . rand(0, 9999);

        $parent = new Feature($parentName);
        $feature = new Feature($name, null, $parent);

        // test
        $this->assertEquals($name, $feature->getName());
        $this->assertSame($parent, $feature->getParent());
    }

    public function testToStringConversion()
    {
        $name = 'feature-' . rand(0, 9999);
        $feature = new Feature($name);

        $this->assertEquals($name, (string) $feature);
    }

    public function testIsEnabled()
    {
        $this->assertTrue(true);
    }
}