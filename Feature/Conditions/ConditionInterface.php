<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

interface ConditionInterface
{
    /**
     * @return string
     */
    public function getName();
    
    public function vote();
}
