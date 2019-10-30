<?php

namespace E7\FeatureFlagsBundle\Feature;

use ArrayIterator;
use Countable;
use E7\FeatureFlagsBundle\Context\ContextInterface;
use E7\FeatureFlagsBundle\Profiler\NullProfile;
use E7\FeatureFlagsBundle\Profiler\ProfileInterface;
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

    /** @var ProfileInterface */
    private $profile;

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
        ProfileInterface $profile = null,
        array $options = []
    ) {
        foreach ($features as $feature) {
            $this->addFeature($feature);
        }

        $this->context = $context;
        $this->profile = $profile ?: new NullProfile();
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
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return !empty($this->features[$name]);
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 == $this->count();
    }

    /**
     * @return ProfileInterface
     */
    public function getProfile()
    {
        return $this->profile;
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
     * @return bool
     */
    public function isEnabled($name)
    {
        $feature = !empty($this->features[$name]) ? $this->features[$name] : null;

        $isEnabled = null !== $feature
            ? $feature->isEnabled($this->context)
            : $this->defaultState;

        $this->profile->hit($name, $isEnabled, $feature);

        return $isEnabled;
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
