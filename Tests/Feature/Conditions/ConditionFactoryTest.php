<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionFactory;
use E7\FeatureFlagsBundle\Feature\Conditions\ResolverInterface;
use E7\FeatureFlagsBundle\Feature\Conditions\TypeResolver;
use E7\PHPUnit\Traits\OopTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ConditionFactoryTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class ConditionFactoryTest extends TestCase
{
    use OopTrait;

    /**
     * @dataProvider provideMagicCall
     * @param array $input
     * @param array $expected
     */
    public function testMagicCall(array $input, array $expected)
    {
        $factory = $this->createFactory();
        $method = 'create' . $input['type'];

        $this->assertObjectHasMethod('__call', $factory);

        $condition = call_user_func_array([$factory, $method], $input['arguments']);
        $this->assertInstanceOf($expected['class'], $condition);
    }

    /**
     * @return array
     */
    public function provideMagicCall()
    {
        return [
            'test-with-bool' => [
                [ 'type' => 'bool', 'arguments' => [ true ] ],
                [ 'class' => BoolCondition::class ]
            ]
        ];
    }

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

        $factory = $this->createFactory();

        $args = $input['args'];
        array_unshift($args, $input['type']);

        $condition = call_user_func_array([$factory, 'create'], $args);

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
                    'type' => 'bool',
                    'args' => [ true ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => true,
                ]
            ],
            'create-bool-from-type-false' => [
                [
                    'type' => 'bool',
                    'args' => [ false ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => false,
                ]
            ],
            'create-bool-from-class-true' => [
                [
                    'type' => BoolCondition::class,
                    'args' => [ true ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => true,
                ]
            ],
            'create-bool-from-class-false' => [
                [
                    'type' => BoolCondition::class,
                    'args' => [ false ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => false,
                ]
            ],
            'unknown-type' => [
                [
                    'type' => 'unknown-condition-type',
                    'args' => [],
                ],
                [
                    'exception' => \Exception::class
                ]
            ],
            'too-many-parameters' => [
                [
                    'type' => 'bool',
                    'args' => [true, true, true],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => true,
                ]
            ]
        ];
    }

    /**
     * @dataProvider providerCreateFromConfig
     * @param array $input
     * @param array $expected
     */
    public function testCreateFromConfig(array $input, array $expected)
    {
        if (!empty($expected['exception'])) {
            $this->expectException($expected['exception']);
        }

        $factory = $this->createFactory();
        $condition = $factory->createFromConfig($input['type'], $input['config']);

        $this->assertInstanceOf($expected['type'], $condition);
        $this->assertEquals($expected['vote_result'], $condition->vote($input['context']));
    }

    /**
     * @return array
     */
    public function providerCreateFromConfig()
    {
        return [
            'create-bool-from-type-true' => [
                [
                    'type' => 'bool',
                    'config' => [ 'flag' => true ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => true,
                ]
            ],
            'create-bool-from-type-false' => [
                [
                    'type' => 'bool',
                    'config' => [ 'flag' => false ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => false,
                ]
            ],
            'create-bool-from-class-true' => [
                [
                    'type' => BoolCondition::class,
                    'config' => [ 'flag' => true ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => true,
                ]
            ],
            'create-bool-from-class-false' => [
                [
                    'type' => BoolCondition::class,
                    'config' => [ 'flag' => false ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
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
            ],
            'too-many-parameters' => [
                [
                    'type' => BoolCondition::class,
                    'config' => [ 'flag' => true, 'foo' => 'bar', 'bazz' => 'bamm' ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => true,
                ]
            ],
            'too-many-parameters-wrong-order' => [
                [
                    'type' => BoolCondition::class,
                    'config' => [ 'foo' => 'bar', 'flag' => true, 'bazz' => 'bamm' ],
                    'context' => new Context(),
                ],
                [
                    'type' => BoolCondition::class,
                    'vote_result' => true,
                ]
            ],
            'missing-parameters' => [
                [
                    'type' => BoolCondition::class,
                    'config' => [ 'foo' => 'bar', 'bazz' => 'bamm' ],
                    'context' => new Context(),
                ],
                [
                    'exception' => \Exception::class
                ]
            ],
        ];
    }

    /**
     * @param ResolverInterface $resolver
     * @return ConditionFactory
     */
    protected function createFactory(ResolverInterface $resolver = null)
    {
        $resolver = $resolver ?: new TypeResolver();

        return new ConditionFactory($resolver);
    }
}
