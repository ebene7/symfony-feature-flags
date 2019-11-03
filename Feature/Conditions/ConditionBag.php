<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use ArrayIterator;
use Countable;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionInterface;
use Exception;
use IteratorAggregate;
use Traversable;

/**
 * Class ConditionBag
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class ConditionBag implements IteratorAggregate, Countable
{
    /** @var boolean */
    private $allowOverrideDefaults;

    /** @var string[] */
    private $defaultKeys = [
        'enabled',
        'disabled',
        'default',
    ];

    /** @var array */
    private $conditions = [];

    /**
     * Constructor
     *
     * @param bool $allowOverrideDefaults
     */
    public function __construct(bool $allowOverrideDefaults = false)
    {
        $this->allowOverrideDefaults = $allowOverrideDefaults;
    }

    /**
     * Add condition
     *
     * @param ConditionInterface $condition
     * @return ConditionBag
     */
    public function add(ConditionInterface $condition)
    {
        $this->set($condition->getName(), $condition);

        return $this;
    }

    /**
     * Set condition
     *
     * @param type $name
     * @param ConditionInterface $condition
     * @return $this
     * @throws Exception
     */
    public function set($name, ConditionInterface $condition)
    {
        if (!$this->allowOverrideDefaults 
            && in_array($name, $this->defaultKeys) 
            && $this->has($name)) {
            throw new Exception('Its not possible to override default key');
        }

        $this->conditions[$name] = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @param string $name
     * @return ConditionInterface|null
     */
    public function get($name)
    {
        return $this->has($name) ? $this->conditions[$name] : null;
    }

    /**
     * Check, if condition exists
     *
     * @param string $name
     * @return boolean
     */
    public function has(string $name)
    {
        return !empty($this->conditions[$name]);
    }

    /**
     * Get all conditions
     *
     * @return array
     */
    public function all()
    {
        return $this->conditions;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->conditions);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->conditions);
    }
}
