<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\CallbackCondition;
use InvalidArgumentException;

/**
 * Class CallbackConditionTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class CallbackConditionTest extends ConditionTestCase
{
    public function testMagicMethodToString()
    {
        $condition = new CallbackCondition(function() {});
        $this->doTestMagicMethodToString($condition);
        $this->doTestToStringConversion($condition);
    }
    
    /**
     * @dataProvider providerConstructorWithException
     * @param array $input
     * @param array $expected
     */
    public function testConstructorWithException(array $input, array $expected)
    {
        if (null !== $expected['exception']) {
            $this->expectException($expected['exception']);
        }
        
        $this->assertInstanceOf(
            CallbackCondition::class, 
            new CallbackCondition($input['callback'])
        );
    }
    
    /**
     * @return array
     */
    public function providerConstructorWithException()
    {
        return [
            'valid-callback' => [
                [ 'callback' => function() { return true; } ],
                [ 'exception' => null ]
            ],
            'invalid-callback-throws-exception' => [
                [ 'callback' => 'this-is-not-a-valid-callback' ],
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
        $condition = new CallbackCondition($input['callback']);
        
        $this->assertEquals($expected['result'], $condition->vote(new Context()));
    }
    
    /**
     * @return array
     */
    public function providerVote()
    {
        return [
            'valid-callback-says-yes' => [
                [ 'callback' => function() { return true; } ],
                [ 'result' => true,
                    'exception' => null ]
            ],
            'valid-callback-says-no' => [
                [ 'callback' => function() { return false; } ],
                [ 'result' => false ]
            ]
        ];
    }
}
