<?php

namespace E7\FeatureFlagsBundle\Profiler;

use E7\FeatureFlagsBundle\Feature\FeatureInterface;

/**
 * Class NullProfile
 * @package E7\FeatureFlagsBundle\Profiler
 */
class NullProfile implements ProfileInterface
{
    /**
     * @inheritDoc
     */
    public function hit(
        string $name,
        bool $isEnabled,
        FeatureInterface $feature = null
    ): ProfileInterface {
        return $this;
    }
}
