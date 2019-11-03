<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\FeatureCondition;
use E7\FeatureFlagsBundle\Feature\Feature;

/**
 * Class FeatureConditionTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class FeatureConditionTest extends ConditionTestCase
{
    public function testConstructor()
    {
        $name = 'name-' . rand(0, 9999);
        $condition = new FeatureCondition('awesome-feature', $name);

        $this->assertEquals($name, $condition->getName());
    }

    public function testMagicMethodToString()
    {
        $condition = new FeatureCondition('awesome-feature');
        $this->doTestMagicMethodToString($condition);
        $this->doTestToStringConversion($condition);
    }

    public function testSetAndGetName()
    {
        $this->doTestGetterAndSetter(new FeatureCondition('awesome-feature'), 'name');
    }

    /**
     * @dataProvider providerVote
     * @param array $input
     * @param array $expected
     */
    public function testVote(array $input, array $expected)
    {
        $feature = new Feature($input['feature_name']);
        $condition = new FeatureCondition($input['name']);
        $context = new Context(['feature' => $feature]);

        $this->assertEquals($expected['match'], $condition->vote($context));
    }

    /**
     * @return array
     */
    public function providerVote()
    {
        return [
            'names-matches' => [
                [ 'feature_name' => 'awesome-feature', 'name' => 'awesome-feature' ],
                [ 'match' => true ]
            ],
            'names-does-not-match' => [
                [ 'feature_name' => 'awesome-feature', 'name' => 'another-awesome-feature' ],
                [ 'match' => false ]
            ],
            'name-match.regex' => [
                [ 'feature_name' => 'awesome-feature', 'name' => '*-feature' ],
                [ 'match' => true ]
            ],
        ];
    }
}
