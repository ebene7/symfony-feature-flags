<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Interface ConditionInterface
 */
interface ConditionInterface
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return boolean
     */
    public function vote(ContextInterface $context);
}
