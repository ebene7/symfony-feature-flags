<?php

namespace E7\FeatureFlagsBundle\Feature;

interface FeatureInterface
{
    /**
     * @return boolean
     */
    public function isEnabled();
}
