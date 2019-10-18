<?php

namespace E7\FeatureFlagsBundle\Feature;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Interface FeatureInterface
 * @package E7\FeatureFlagsBundle\Feature
 */
interface FeatureInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return boolean
     */
    public function isEnabled(ContextInterface $context);
}
