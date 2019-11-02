<?php

namespace E7\FeatureFlagsBundle\Tests\DataCollector;

use E7\FeatureFlagsBundle\DataCollector\FeatureCollector;
use E7\PHPUnit\Traits\OopTrait;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class FeatureCollectorTest
 * @package E7\FeatureFlagsBundle\Tests\DataCollector
 */
class FeatureCollectorTest extends TestCase
{
    use OopTrait;

    public function testInstanceOf()
    {
        $reflection = new ReflectionClass(FeatureCollector::class);
        $collector = $reflection->newInstanceWithoutConstructor();

        $this->assertInstanceOf(DataCollector::class, $collector);
    }

    public function testGetName()
    {
        $reflection = new ReflectionClass(FeatureCollector::class);
        $collector = $reflection->newInstanceWithoutConstructor();

        $this->doTestGetter($collector, 'name', 'feature-flags');
    }

    public function testGetFeatureCount()
    {
        $reflection = new ReflectionClass(FeatureCollector::class);
        $collector = $reflection->newInstanceWithoutConstructor();

        $this->doTestGetter($collector, 'FeatureCount', 0);
    }

    public function testGetHitCount()
    {
        $reflection = new ReflectionClass(FeatureCollector::class);
        $collector = $reflection->newInstanceWithoutConstructor();

        $this->doTestGetter($collector, 'HitCount', 0);
    }

    public function testGetMissingCount()
    {
        $reflection = new ReflectionClass(FeatureCollector::class);
        $collector = $reflection->newInstanceWithoutConstructor();

        $this->doTestGetter($collector, 'MissingCount', 0);
    }

    public function testGetHits()
    {
        $reflection = new ReflectionClass(FeatureCollector::class);
        $collector = $reflection->newInstanceWithoutConstructor();

        $this->doTestGetter($collector, 'hits', []);
    }

    public function testGetFeatures()
    {
        $reflection = new ReflectionClass(FeatureCollector::class);
        $collector = $reflection->newInstanceWithoutConstructor();

        $this->doTestGetter($collector, 'features', []);
    }
}
