<?php

namespace E7\FeatureFlagsBundle\Feature;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Class FeatureBox
 * @package E7\FeatureFlagsBundle\Feature
 */
class FeatureBox
{
    /** @var array */
    private $features = [];

    /** @var ContextInterface */
    private $context;

    /**
     * FeatureBox constructor.
     * @param $features
     * @param ContextInterface $context
     */
    public function __construct($features, ContextInterface $context)
    {
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }

        $this->context = $context;
    }

    /**
     * @param Feature $feature
     * @return FeatureBox
     */
    public function addFeature(Feature $feature)
    {
        $this->features[$feature->getName()] = $feature;

        return $this;
    }

    /**
     * @param $name
     */
    public function isEnabled($name)
    {
        $this->features[$name]->isEnabled();
    }
}
