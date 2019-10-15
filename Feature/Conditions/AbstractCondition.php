<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Class AbstractCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @inheritDoc
     */
    public function vote(ContextInterface $context)
    {
        return $this->doVote($context);
    }

    protected abstract function doVote(ContextInterface $context);
}
