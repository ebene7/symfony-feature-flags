<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use Countable;
use IteratorAggregate;

/**
 * Interface ChainConditionInterface
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
interface ChainConditionInterface extends ConditionInterface, IteratorAggregate, Countable
{
}
