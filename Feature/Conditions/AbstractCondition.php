<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Class AbstractCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
abstract class AbstractCondition implements ConditionInterface
{
    /** @var string */
    private $name;

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return sprintf("%s: %s",$this->getName() , $this->getType());
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): AbstractCondition
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return (string) $this->name;
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
