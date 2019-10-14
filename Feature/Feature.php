<?php

namespace E7\FeatureFlagsBundle\Feature;

class Feature implements FeatureInterface
{
    /** @var string */
    private $name;
    
    /** @var FeatureInterface */
    private $parent;
    
    private $conditions = [];
    
    public function __construct(
        string $name, 
        $conditions,
        FeatureInterface $parent
    ) {
        $this->name = $name;
        $this->setConditions($conditions);
        $this->parent = $parent;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    public function addCondition($name, $config = null)
    {
        
    }
    
    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return true;
    }
}
