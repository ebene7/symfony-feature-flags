<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\BooleanCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class ConditionFactoryTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class ConditionFactoryTest extends TestCase
{
    /**
     * @dataProvider providerCreate
     * @param array $input
     * @param array $expected
     */
    public function testCreate(array $input, array $expected)
    {
        if (!empty($expected['exception'])) {
            $this->expectException($expected['exception']);
        }
        
        $factory = new ConditionFactory();
        $condition = $factory->create($input['type'], $input['config']);
        
        $this->assertInstanceOf($expected['type'], $condition);
        $this->assertEquals($expected['vote_result'], $condition->vote($input['context']));
    }

    /**
     * @return array
     */
    public function providerCreate()
    {
        return [
            'create-bool-from-type-true' => [
                [
                    'type' => 'boolean',
                    'config' => [ 'flag' => true ],
                    'context' => new Context(),
                ],
                [
                    'type' => BooleanCondition::class,
                    'vote_result' => true,
                ]
            ],
            'create-bool-from-type-false' => [
                [
                    'type' => 'boolean',
                    'config' => [ 'flag' => false ],
                    'context' => new Context(),
                ],
                [
                    'type' => BooleanCondition::class,
                    'vote_result' => false,
                ]
            ],
            'create-bool-from-class-true' => [
                [
                    'type' => BooleanCondition::class,
                    'config' => [ 'flag' => true ],
                    'context' => new Context(),
                ],
                [
                    'type' => BooleanCondition::class,
                    'vote_result' => true,
                ]
            ],
            'create-bool-from-class-false' => [
                [
                    'type' => BooleanCondition::class,
                    'config' => [ 'flag' => false ],
                    'context' => new Context(),
                ],
                [
                    'type' => BooleanCondition::class,
                    'vote_result' => false,
                ]
            ],
            'unknown-type' => [
                [
                    'type' => 'unknown-condition-type',
                    'config' => [],
                ],
                [
                    'exception' => \Exception::class
                ]
            ]
        ];
    }
}
