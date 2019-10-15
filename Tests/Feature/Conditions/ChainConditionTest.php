<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use ArrayIterator;
use Countable;
use E7\FeatureFlagsBundle\Feature\Conditions\ChainCondition;
use IteratorAggregate;

/**
 * Class ChainConditionTest
 */
class ChainConditionTest extends ConditionTestCase
{
    public function testToStringConversion()
    {
        $this->doTestToStringConversion(new ChainCondition());
    }

//    public function testVoteDefaultReturnValue()
//    {
//        $condition = new ChainCondition();
//
//        $this->assertTrue($condition->vote());
//    }

    public function testIteratorAggregateInterface()
    {
        $condition = new ChainCondition();

        $this->assertInstanceOf(IteratorAggregate::class, $condition);
        $this->assertTrue(method_exists($condition, 'getIterator'));
        $this->assertInstanceOf(ArrayIterator::class, $condition->getIterator());
    }

    public function testCountableInterface()
    {
        $condition = new ChainCondition();

        $this->assertInstanceOf(Countable::class, $condition);
        $this->assertTrue(method_exists($condition, 'count'));
        $this->assertCount(0, $condition);

        $condition->addCondition(new ChainCondition());
        $this->assertCount(1, $condition);
    }
}