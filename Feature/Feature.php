<?php

namespace E7\FeatureFlagsBundle\Feature;

use E7\FeatureFlagsBundle\Feature\Conditions\ChainCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionInterface;

class Feature implements FeatureInterface
{
    /** @var string */
    private $name;

    /** @var ChainCondition */
    private $conditions;

    /** @var FeatureInterface */
    private $parent;

    /**
     * Feature constructor.
     * @param string                $name
     * @param ChainCondition        $conditions
     * @param FeatureInterface|null $parent
     */
    public function __construct(
        string $name,
        ChainCondition $conditions = null,
        FeatureInterface $parent = null
    ) {
        $this->name = $name;
        $this->conditions = $conditions ?: new ChainCondition();
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param ConditionInterface $condition
     * @return Feature
     */
    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions->addCondition($condition);

        return $this;
    }

    /**
     * @return FeatureInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        $enabled = true;

        return true;
    }
}
