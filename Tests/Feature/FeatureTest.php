<?php

namespace E7\FeatureFlagsBundle\Tests\Feature;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\FeatureCondition;
use E7\FeatureFlagsBundle\Feature\Feature;
use E7\FeatureFlagsBundle\Feature\FeatureInterface;
use E7\PHPUnit\Traits\OopTrait;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class FeatureTest
 * @package E7\FeatureFlagsBundle\Tests\Feature
 */
class FeatureTest extends TestCase
{
    use OopTrait;

    public function testInstanceOfFeatureInterface()
    {
        $this->assertTrue(new Feature('awesome-feature', null) instanceof FeatureInterface);
    }

    public function testConstructorPassesParameters()
    {
        $name = 'feature-' . rand(0, 9999);
        $parentName = 'parent-feature-' . rand(0, 9999);

        $parent = new Feature($parentName);
        $feature = new Feature($name, null, $parent);

        // test
        $this->assertEquals($name, $feature->getName());
        $this->assertSame($parent, $feature->getParent());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorThrowsExceptionWithEmptyName()
    {
        new Feature('');
    }

    public function testMagicMethodToString()
    {
        $this->doTestMagicMethodToString(new Feature('awesome-feature'));
    }

    public function testToStringConversion()
    {
        $name = 'feature-' . rand(0, 9999);
        $feature = new Feature($name);

        $this->assertEquals($name, (string) $feature);
    }

    public function testIsEnabled()
    {
        $this->assertTrue(true);
    }

    public function testIsEnabledWithoutCondition()
    {
        $feature = new Feature('awesome-feature-without-condition');
        $this->assertTrue($feature->isEnabled(new Context()));
    }

    public function testGetterAndSetterParent()
    {
        $parent = new Feature('awesome-parent-feature');
        $feature = new Feature('awesome-feature');

        $this->doTestGetterAndSetter($feature, 'parent', $parent);
    }

    public function testSetParentFeatureCannotBeOwnParent()
    {
        $this->expectException(RuntimeException::class);

        $feature = new Feature('awesome-feature');
        $feature->setParent($feature);
    }

    /**
     * @dataProvider providerIsEnabledWithParentFeature
     * @param array $input
     * @param array $expected
     */
    public function testIsEnabledWithParentFeature(array $input, array $expected)
    {
        // prepare
        $parent = new Feature('parent-feature');
        $parent->addCondition(new BoolCondition($input['parent_flag']));

        $child = new Feature('child-feature', null, $parent);
        $child->addCondition(new BoolCondition($input['child_flag']));

        $context = new Context();

        // test
        $this->assertInternalType('bool', $parent->isEnabled($context));
        $this->assertEquals($expected['parent_result'], $parent->isEnabled($context));

        $this->assertInternalType('bool', $child->isEnabled($context));
        $this->assertEquals($expected['child_result'], $child->isEnabled($context));
    }

    /**
     * @return array
     */
    public function providerIsEnabledWithParentFeature()
    {
        return [
            'both-true' => [
                [ 'parent_flag' => true, 'child_flag' => true ],
                [ 'parent_result' => true, 'child_result' => true ]
            ],
            'both-false' => [
                [ 'parent_flag' => false, 'child_flag' => false ],
                [ 'parent_result' => false, 'child_result' => false ]
            ],
            'parent-true-child-false' => [
                [ 'parent_flag' => true, 'child_flag' => false ],
                [ 'parent_result' => true, 'child_result' => false ]
            ],
            'parent-false-child-true' => [
                [ 'parent_flag' => false, 'child_flag' => true ],
                [ 'parent_result' => false, 'child_result' => false ]
            ],
        ];
    }

    /**
     * @dataProvider providerIsEnabledPassesFeatureIntoContext
     * @param array $input
     * @param array $expected
     */
    public function testIsEnabledPassesFeatureIntoContext(array $input, array $expected)
    {
        // prepare
        $feature = new Feature($input['feature_name']);
        $condition = new FeatureCondition($input['name']);
        $feature->addCondition($condition);
        $context = new Context();

        // test
        $this->assertFalse($context->has('feature'));
        $this->assertEquals($expected['match'], $feature->isEnabled($context));
        $this->assertFalse($context->has('feature'));
    }

    /**
     * @return array
     */
    public function providerIsEnabledPassesFeatureIntoContext()
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
