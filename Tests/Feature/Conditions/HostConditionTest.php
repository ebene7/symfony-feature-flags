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

    public function testSetAndGetName()
    {
        $this->doTestGetterAndSetter(new HostCondition('*.example.com'), 'name');
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

        $this->assertInstanceOf(HostCondition::class, new HostCondition($input['hosts']));
    }

    /**
     * @return array
     */
    public function providerConstructorWithHostsParameter()
    {
        return [
            'string-parameter' => [
                [ 'hosts' => 'example.com' ],
                [ 'exception' => null ]
            ],
            'array-parameter' => [
                [ 'hosts' => [ 'example.com',  '*.example.com' ] ],
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
    public function testVote(array $input, array $expected)
    {
        if (null !== $expected['exception']) {
            $this->expectException($expected['exception']);
        }

        $condition = new HostCondition($input['hosts']);
        $context = new Context(['host' => $input['host']]);

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
                    'host' => 'example.com',
                    'hosts' => 'example.com'
                ],
                [
                    'match' => true,
                    'exception' => null
                ]
            ],
            'array-parameter' => [
                [
                    'host' => 'sub.example.com',
                    'hosts' => [ 'example.com',  '*.example.com' ]
                ],
                [
                    'match' => true,
                    'exception' => null
                ]
            ],
            'number-parameter' => [
                [
                    'host' => null,
                    'hosts' => 42
                ],
                [
                    'match' => false,
                    'exception' => InvalidArgumentException::class
                ]
            ],
        ];
    }
}
