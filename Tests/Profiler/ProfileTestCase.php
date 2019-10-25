<?php

namespace E7\FeatureFlagsBundle\Tests\Profiler;

use E7\FeatureFlagsBundle\Profiler\ProfileInterface;
use E7\PHPUnit\Traits\OopTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ProfileTestCase
 * @package E7\FeatureFlagsBundle\Tests\Profiler
 */
abstract class ProfileTestCase extends TestCase
{
    use OopTrait;

    /**
     * Test Template for ProfileInterface::hit()
     * 
     * @param ProfileInterface $profile
     */
    public function doTestHit(ProfileInterface $profile)
    {
        $this->assertObjectHasMethod('hit', $profile);
        $this->assertInstanceOf(ProfileInterface::class, $profile->hit('awesome-feature', true));
    }
}
