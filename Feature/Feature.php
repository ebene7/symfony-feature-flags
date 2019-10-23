<?php

namespace E7\FeatureFlagsBundle\Feature;

use E7\FeatureFlagsBundle\Context\ContextInterface;
use E7\FeatureFlagsBundle\Feature\Conditions\ChainCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionInterface;
use InvalidArgumentException;

/**
 * Class Feature
 * @package E7\FeatureFlagsBundle\Feature
 */
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
        if (empty($name)) {
            throw new InvalidArgumentException('Parameter $name must not be empty.');
        }

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
    public function getName(): string
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
     * @return ChainCondition
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return boolean
     */
    public function hasConditions()
    {
        return 0 < count($this->conditions);
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
    public function isEnabled(ContextInterface $context)
    {
        $context->set('feature', $this);
        $context->set('feature.name', $this->getName());

        $isEnabled = $this->conditions->vote($context)
            && (null !== $this->parent ? $this->parent->isEnabled($context) : true);

        $context->remove('feature', 'feature.name');

        return $isEnabled;
    }
}
