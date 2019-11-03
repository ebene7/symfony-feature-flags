<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition;

/**
 * Class BooleanConditionTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class BoolConditionTest extends ConditionTestCase
{
    public function testConstructor()
    {
        $name = 'name-' . rand(0, 9999);
        $condition = new BoolCondition(true, $name);

        $this->assertEquals($name, $condition->getName());
    }

    public function testMagicMethodToString()
    {
        $condition = new BoolCondition(true);
        $this->doTestMagicMethodToString($condition);
        $this->doTestToStringConversion($condition);
    }

    public function testSetAndGetName()
    {
        $this->doTestGetterAndSetter(new BoolCondition(true), 'name');
    }

    /**
     * @dataProvider providerVote
     * @param bool $flag
     */
    public function testVote(bool $flag)
    {
        $condition = new BoolCondition($flag);
        $context = new Context([]);
        
        $this->assertEquals($flag, $condition->vote($context));
    }

    /**
     * @return array
     */
    public function providerVote()
    {
        return [
            'just-true' => [ true ],
            'just-false' => [ false ],
        ];
    }
}
