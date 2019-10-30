<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Feature\Conditions\TypeResolver;
use PHPUnit\Framework\TestCase;

/**
 * Class ResolverTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class ResolverTest extends TestCase
{
    public function testResolve()
    {
        $type = 'bool';
        $class = 'E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition';

        $resolver = new TypeResolver();

        $this->assertEquals($class, $resolver->resolve($type));
    }
}