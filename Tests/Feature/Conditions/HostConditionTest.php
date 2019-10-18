<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\HostCondition;
use InvalidArgumentException;

/**
 * Class HostConditionTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class HostConditionTest extends ConditionTestCase
{
    public function testMagicMethodToString()
    {
        $condition = new HostCondition('http://example.com');
        $this->doTestMagicMethodToString($condition);
        $this->doTestToStringConversion($condition);
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

        $this->assertInstanceOf(HostCondition::class, new HostCondition($input['hostnames']));
    }

    /**
     * @return array
     */
    public function providerConstructorWithHostsParameter()
    {
        return [
            'string-parameter' => [
                [ 'hostnames' => 'example.com' ],
                [ 'exception' => null ]
            ],
            'array-parameter' => [
                [ 'hostnames' => [ 'example.com',  '*.example.com' ] ],
                [ 'exception' => null ]
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
    public function testVote(array $input, array $expected)
    {
        if (null !== $expected['exception']) {
            $this->expectException($expected['exception']);
        }

        $condition = new HostCondition($input['hostnames']);
        $context = new Context(['hostname' => $input['hostname']]);

        $this->assertEquals($expected['match'], $condition->vote($context));
    }

    /**
     * @return array
     */
    public function providerVote()
    {
        return [
            'string-parameter' => [
                [
                    'hostname' => 'example.com',
                    'hostnames' => 'example.com'
                ],
                [
                    'match' => true,
                    'exception' => null
                ]
            ],
            'array-parameter' => [
                [
                    'hostname' => 'sub.example.com',
                    'hostnames' => [ 'example.com',  '*.example.com' ]
                ],
                [
                    'match' => true,
                    'exception' => null
                ]
            ],
            'number-parameter' => [
                [
                    'hostname' => null,
                    'hostnames' => 42
                ],
                [
                    'match' => false,
                    'exception' => InvalidArgumentException::class
                ]
            ],
        ];
    }
}
