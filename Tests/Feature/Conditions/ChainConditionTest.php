<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use ArrayIterator;
use Countable;
use E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\ChainCondition;
use IteratorAggregate;

/**
 * Class ChainConditionTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class ChainConditionTest extends ConditionTestCase
{
    public function testConstructor()
    {
        $name = 'name-' . rand(0, 9999);
        $condition = new ChainCondition([], $name);

        $this->assertEquals($name, $condition->getName());
    }

    public function testMagicMethodToString()
    {
        $condition = new ChainCondition();
        $this->doTestMagicMethodToString($condition);
        $this->doTestToStringConversion($condition);
    }

    public function testSetAndGetName()
    {
        $this->doTestGetterAndSetter(new ChainCondition(), 'name');
    }

    /**
     * @dataProvider providerAddMemberConditionsViaConstructor
     * @param array $input
     * @param array $expected
     */
    public function testAddMemberConditionsViaConstructor(array $input, array $expected)
    {
        $chain = new ChainCondition($input['members']);

        $this->assertCount(count($input['members']), $chain);
    }

    /**
     * @return array
     */
    public function providerAddMemberConditionsViaConstructor()
    {
        return [
            'with-two-valid-conditions' => [
                [ 'members' => [ new BoolCondition(true), new BoolCondition(true) ] ],
                []
            ],
        ];
    }

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