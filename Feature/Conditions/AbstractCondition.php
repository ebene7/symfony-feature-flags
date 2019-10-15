<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

/**
 * Class AbstractCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }
}
