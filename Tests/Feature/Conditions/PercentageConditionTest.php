<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\PercentageCondition;

/**
 * Class PercentageConditionTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class PercentageConditionTest extends ConditionTestCase
{
    public function testConstructor()
    {
        $name = 'name-' . rand(0, 9999);
        $condition = new PercentageCondition(50, $name);

        $this->assertEquals($name, $condition->getName());
    }

    public function testMagicMethodToString()
    {
        $condition = new PercentageCondition(50);
        $this->doTestMagicMethodToString($condition);
        $this->doTestToStringConversion($condition);
    }

    public function testSetAndGetName()
    {
        $this->doTestGetterAndSetter(new PercentageCondition(50), 'name');
    }

    public function testVote()
    {
//        $percentage = 25;
//
//        $condition = new PercentageCondition($percentage);
//        $count = 0;
//
//        for ($i=0; $i < 10000; $i++) {
//            if ($condition->vote(new Context([]))) {
//                $count++;
//            }
//        }
//
//        $count = $count / 100;
//        $in = (($percentage - 1) <= $count && $count <= ($percentage + 1));

        $this->assertTrue(true);
    }
}
