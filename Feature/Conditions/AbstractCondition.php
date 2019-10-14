<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

abstract class AbstractCondition implements ConditionInterface
{
    private $name;
    
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }
}
