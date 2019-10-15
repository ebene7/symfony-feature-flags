<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Feature\Conditions\ConditionInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ConditionTestCase
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class ConditionTestCase extends TestCase
{
    /**
     * Template for string conversion test
     *
     * @param ConditionInterface $condition
     */
    protected function doTestToStringConversion(ConditionInterface $condition)
    {
        $this->assertTrue(method_exists($condition, '__toString'));
        $this->assertEquals($condition->getName(), (string) $condition);
    }
}