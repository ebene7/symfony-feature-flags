<?php

namespace E7\FeatureFlagsBundle\Twig;

use E7\FeatureFlagsBundle\Feature\FeatureBox;

class FeatureExtension extends \Twig_Extension
{
    /** @var FeatureBox */
    private $features;
    
    /**
     * Constructor
     * 
     * @param FeatureBox $features
     */
    public function __construct(FeatureBox $features)
    {
        $this->features = $features;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('feature_enabled', [$this, 'isFeatureEnabled'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param string $name
     * @param array  $arguments
     * @return bool
     */
    public function isFeatureEnabled($name)
    {
        return $this->features->isEnabled($name);
    }
    
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'e7_feature_extension';
    }
}
