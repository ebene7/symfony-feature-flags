<?php

namespace E7\FeatureFlagsBundle\Feature;

/**
 * Class FeatureBox
 * @package E7\FeatureFlagsBundle\Feature
 */
class FeatureBox
{
    private $features = [];

    /**
     * FeatureBox constructor.
     * @param $features
     */
    public function __construct($features)
    {
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }
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
