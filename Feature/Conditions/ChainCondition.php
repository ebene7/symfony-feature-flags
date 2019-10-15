<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use ArrayIterator;
use Countable;
use E7\FeatureFlagsBundle\Context\ContextInterface;
use IteratorAggregate;
use Traversable;

/**
 * Class ChainCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class ChainCondition extends AbstractCondition implements IteratorAggregate, Countable
{
    /** @var array */
    private $conditions = [];

    /**
     * @return string
     */
    public function getName()
    {
        return 'chain';
    }

    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        /** @var ConditionInterface $condition */
        foreach ($this->conditions as $condition) {
            if (!$condition->vote($context)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ConditionInterface $condition
     * @return ChainCondition
     */
    public function addCondition(ConditionInterface $condition)
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->conditions);
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->conditions);
    }
}