<?php

namespace E7\FeatureFlagsBundle\Tests\Profiler;

use E7\FeatureFlagsBundle\Profiler\Profile;

/**
 * Class NullProfilerTest
 * @package E7\FeatureFlagsBundle\Tests\Profiler
 */
class NullProfilerTest extends ProfileTestCase
{
    public function testHit()
    {
        $profile = new Profile();
        $this->doTestHit($profile);
    }
}
