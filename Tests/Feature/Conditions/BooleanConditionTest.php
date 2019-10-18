<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\BooleanCondition;

/**
 * Class BooleanConditionTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class BooleanConditionTest extends ConditionTestCase
{
    public function testMagicMethodToString()
    {
        $condition = new BooleanCondition(true);
        $this->doTestMagicMethodToString($condition);
        $this->doTestToStringConversion($condition);
    }

    /**
     * @dataProvider providerVote
     * @param bool $flag
     */
    public function testVote(bool $flag)
    {
        $condition = new BooleanCondition($flag);
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
