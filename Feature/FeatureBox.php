<?php

namespace E7\FeatureFlagsBundle\Feature;

class FeatureBox
{
    private $features = [];
    
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
    
    public function isEnabled($name)
    {
        $this->features[$name]->isEnabled();
    }
}
