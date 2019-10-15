<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Feature\Conditions\HostCondition;

/**
 * Class HostConditionTest
 */
class HostConditionTest extends ConditionTestCase
{
    public function testToStringConversion()
    {
        $this->doTestToStringConversion(new HostCondition('http://example.com'));
    }

    /**
     * @dataProvider providerVote
     * @param array $input
     * @param array $expected
     */
//    public function testVote(array $input, array $expected)
//    {
//        $condition = new HostCondition($input['hosts']);
//        
//        $this->assertInternalType('bool', $condition->vote($context));
//    }

    /**
     * @return array
     */
    public function providerVote()
    {
        return [
            'single_hostname' => [
                [
                    'hosts' => 'http://example.com',
                    'context' => null,
                ],
                [
                    'result' => true,
                ]
            ]
        ];
    }
}
