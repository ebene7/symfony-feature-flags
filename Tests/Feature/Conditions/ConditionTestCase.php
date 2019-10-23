<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Feature\Conditions\ConditionInterface;
use E7\PHPUnit\Traits\OopTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class ConditionTestCase
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class ConditionTestCase extends TestCase
{
    use OopTrait;

    /**
     * Template for string conversion test
     *
     * @param ConditionInterface $condition
     */
    protected function doTestToStringConversion(ConditionInterface $condition)
    {
        $expected = sprintf("%s: %s", $condition->getType(), $condition->getName());
        $this->assertEquals($expected, (string) $condition);
    }
}