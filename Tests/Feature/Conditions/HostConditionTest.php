<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
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

        $condition = new HostCondition($input['hostnames']);
        $context = new Context(['hostname' => $input['hostname']]);

        $this->assertEquals($expected['match'], $condition->vote($context));

//        $this->assertInstanceOf(HostCondition::class, $condition);
    }

    /**
     * @return array
     */
    public function providerConstructorWithHostsParameter()
    {
        return [
            'string-parameter' => [
                [
                    'hostname' => 'example.com',
                    'hostnames' => 'example.com',
                ],
                [
                    'match' => true,
                    'exception' => null,
                ]
            ],
            'array-parameter' => [
                [
                    'hostname' => 'http://sub.example.com',
                    'hostnames' => [ 'example.com',  '*.example.com' ]
                ],
                [
                    'match' => true,
                    'exception' => null
                ]
            ],
            'number-parameter' => [
                [ 'hostnames' => 42 ],
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
