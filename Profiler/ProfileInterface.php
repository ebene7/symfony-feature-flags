<?php

namespace E7\FeatureFlagsBundle\Profiler;

use E7\FeatureFlagsBundle\Feature\FeatureInterface;

/**
 * Interface ProfileInterface
 * @package E7\FeatureFlagsBundle\Profiler
 */
interface ProfileInterface
{
    /**
     * @param string $name
     * @param bool $isEnabled
     * @param \E7\FeatureFlagsBundle\Profiler\FeatureInterface $feature
     * @return ProfileInterface
     */
    public function hit(
        string $name,
        bool $isEnabled,
        FeatureInterface $feature = null
    ): ProfileInterface;
}
