<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\HostCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\PercentageCondition;

/**
 * Class PercentageConditionTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class PercentageConditionTest extends ConditionTestCase
{
    public function testToStringConversion()
    {
        $this->doTestToStringConversion(new PercentageCondition(0));
    }

    public function testVote()
    {
        $percentage = 25;

        $condition = new PercentageCondition($percentage);
        $count = 0;

        for ($i=0; $i < 10000; $i++) {
            if ($condition->vote(new Context([]))) {
                $count++;
            }
        }

        $count = $count / 100;
        $in = (($percentage - 1) <= $count && $count <= ($percentage + 1));

        $this->assertTrue($in);
    }
}
