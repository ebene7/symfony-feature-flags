<?php

namespace E7\FeatureFlagsBundle\Feature;

use ArrayIterator;
use Countable;
use E7\FeatureFlagsBundle\Context\ContextInterface;
use IteratorAggregate;

/**
 * Class FeatureBox
 * @package E7\FeatureFlagsBundle\Feature
 */
class FeatureBox implements IteratorAggregate, Countable
{
    /** @var array */
    private $features = [];

    /** @var ContextInterface */
    private $context;

    /** @var boolean */
    private $defaultState = false;

    /**
     * FeatureBox constructor.
     * @param $features
     * @param ContextInterface $context
     * @param array $options
     */
    public function __construct(
        $features,
        ContextInterface $context,
        array $options = []
    ) {
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }

        $this->context = $context;
    }

    /**
     * @param Feature $feature
     * @return FeatureBox
     */
    public function addFeature(Feature $feature)
    {
        $this->features[$feature->getName()] = $feature;

        return $this;
    }

    public function getFeature($name)
    {
        return !empty($this->features[$name]) ? $this->features[$name] : null;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 == $this->count();
    }

    /**
     * @param bool $state
     * @return FeatureBox
     */
    public function setDefaultState(bool $state)
    {
        $this->defaultState = $state;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getDefaultState()
    {
        return $this->defaultState;
    }

    /**
     * @param $name
     */
    public function isEnabled($name)
    {
        return !empty($this->features[$name])
            ? $this->features[$name]->isEnabled()
            : $this->defaultState;
    }

    public function count(): int
    {
        return count($this->features);
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->features);
    }
}
