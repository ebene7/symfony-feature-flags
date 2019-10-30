<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\HostCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\TypeResolver;
use PHPUnit\Framework\TestCase;

/**
 * Class ResolverTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class ResolverTest extends TestCase
{
    /**
     * @dataProvider providerResolve
     * @param array $input
     * @param array $expected
     */
    public function testResolve(array $input, array $expected)
    {
        $resolver = new TypeResolver();

        $this->assertEquals($expected['class'], $resolver->resolve($input['type']));
    }

    /**
     * @return array
     */
    public function providerResolve()
    {
        return [
            'base-type-bool-with-existing-class' => [
                [ 'type' => 'bool' ],
                [ 'class' => BoolCondition::class ],
            ],
            'base-class-host-with-existing-class' => [
                [ 'type' => HostCondition::class ],
                [ 'class' => HostCondition::class ],
            ],
            'unknown-type-bool-without-existing-class' => [
                [ 'type' => 'unknown-but-awesome' ],
                [ 'class' => null ],
            ],
            'unknown-class-bool-without-existing-class' => [
                [ 'type' => 'E7\FeatureFlagsBundle\Feature\Conditions\UnknownButAwesomeCondition' ],
                [ 'class' => null ],
            ],
        ];
    }
}