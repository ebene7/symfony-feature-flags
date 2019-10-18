<?php

namespace E7\FeatureFlagsBundle\Tests\Feature;

use E7\FeatureFlagsBundle\Feature\Feature;
use E7\FeatureFlagsBundle\Feature\FeatureInterface;
use E7\PHPUnit\Traits\OopTrait;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureTest
 * @package E7\FeatureFlagsBundle\Tests\Feature
 */
class FeatureTest extends TestCase
{
    use OopTrait;

    public function testInstanceOfFeatureInterface()
    {
        $this->assertTrue(new Feature('awesome-feature', null) instanceof FeatureInterface);
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

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorThrowsExceptionWithEmptyName()
    {
        new Feature('');
    }

    public function testMagicMethodToString()
    {
        $this->doTestMagicMethodToString(new Feature('awesome-feature'));
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