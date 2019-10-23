<?php

namespace E7\FeatureFlagsBundle\Tests\Feature;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition;
use E7\FeatureFlagsBundle\Feature\Feature;
use E7\FeatureFlagsBundle\Feature\FeatureInterface;
use E7\PHPUnit\Traits\OopTrait;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

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

//    public function testIsEnabledPassesFeatureIntoContext()
//    {
//        // prepare
//        $feature = new Feature('awesome-feature');
//        $context = $this->getMockBuilder(Context::class)
//            ->setMethods(['set', 'remove'])
//            ->getMock();
//
//        $context->expects($this->any())->method('set')->with($this->$this->callback(function () { print_r(func_num_args()); return true; }));
//
//
//        $context->expects($this->any())->method('remove')->with($this->callback(function () { print_r(func_get_args()); return true; }));
//
//        $this->assertInternalType('bool', $feature->isEnabled($context));
//    }
}