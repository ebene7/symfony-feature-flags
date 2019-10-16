<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Feature\Conditions\HostCondition;
use InvalidArgumentException;

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
     * @dataProvider providerConstructorWithHostsParameter
     * @param array $input
     * @param array $expected
     */
    public function testConstructorWithException(array $input, array $expected)
    {
        if (null !== $expected['exception']) {
            $this->expectException($expected['exception']);
        }

        $condition = new HostCondition($input['hosts']);

        $this->assertInstanceOf(HostCondition::class, $condition);
    }

    /**
     * @return array
     */
    public function providerConstructorWithHostsParameter()
    {
        return [
            'string-parameter' => [
                [ 'hosts' => 'http://example.com' ],
                [ 'exception' => null ]
            ],
            'array-parameter' => [
                [ 'hosts' => [ 'http://example.com',  'http://sub.example.com' ] ],
                [ 'exception' => null ]
            ],
            'number-parameter' => [
                [ 'hosts' => 42 ],
                [ 'exception' => InvalidArgumentException::class ]
            ],
        ];
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
