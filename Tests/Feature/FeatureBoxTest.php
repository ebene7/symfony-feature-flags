<?php

namespace E7\FeatureFlagsBundle\Tests\Feature;

use ArrayIterator;
use E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition;
use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Context\ContextInterface;
use E7\FeatureFlagsBundle\Feature\Feature;
use E7\FeatureFlagsBundle\Feature\FeatureBox;
use E7\FeatureFlagsBundle\Profiler\NullProfile;
use E7\FeatureFlagsBundle\Profiler\Profile;
use E7\FeatureFlagsBundle\Profiler\ProfileInterface;
use PHPUnit\Framework\TestCase;
use E7\PHPUnit\Traits\OopTrait;

/**
 * Class FeatureBoxTest
 * @package E7\FeatureFlagsBundle\Tests\Feature
 */
class FeatureBoxTest extends TestCase
{
    use OopTrait;
    
    public function testGetProfileWithDefaultProfile()
    {
        $featureBox = $this->createFeatureBox();

        $this->assertObjectHasMethod('getProfile', $featureBox);
        $this->assertInstanceOf(ProfileInterface::class, $featureBox->getProfile());
        $this->assertInstanceOf(NullProfile::class, $featureBox->getProfile());
    }

    /**
     * @dataProvider providerDefaultState
     * @param boolean $state
     */
    public function testEnabledWithoutConditionsWithDefaultState(bool $state)
    {
        $featureBox = $this->createFeatureBox();
        $featureBox->setDefaultState($state);

        $this->assertEquals($state, $featureBox->isEnabled('unknown-feature'));
    }

    /**
     * @dataProvider providerDefaultState
     * @param boolean $state
     */
    public function testSetterAndGetterDefaultState(bool $state)
    {
        $featureBox = $this->createFeatureBox();

        // initial default state is false
        $this->assertFalse($featureBox->getDefaultState());
        $this->assertFalse($featureBox->isEnabled('unknown-feature'));
        
        $this->assertTrue(method_exists($featureBox, 'setDefaultState'));
        $this->assertSame($featureBox, $featureBox->setDefaultState($state));
        
        $this->assertTrue(method_exists($featureBox, 'getDefaultState'));
        $this->assertInternalType('bool', $featureBox->getDefaultState());
        $this->assertEquals($state, $featureBox->getDefaultState());
    }
    
    /**
     * @return array
     */
    public function providerDefaultState()
    {
        return [
            'default-feature-true' => [ true ],
            'default-feature-false' => [ false ],
        ];
    }

    public function testSplIteratorAggregateImplementation()
    {
        $featureBox = $this->createFeatureBox();
        $this->doTestSplIteratorAggregateImplementation($this->createFeatureBox());
    }
    
    public function testGetIterator()
    {
        $featureBox = $this->createFeatureBox();
        
        $this->assertInstanceOf(ArrayIterator::class, $featureBox->getIterator());
        $this->assertCount(0, $featureBox->getIterator());
        
        $featureBox->addFeature(new Feature('awesome-feature'));
        $this->assertCount(1, $featureBox->getIterator());
    }
    
    public function testSplCountableImplementation()
    {
        $this->doTestSplCountableImplementation($this->createFeatureBox());
    }

    public function testCount()
    {
        $featureBox = $this->createFeatureBox();
        $this->assertCount(0, $featureBox);
        
        $featureBox->addFeature(new Feature('awesome-feature'));
        $this->assertCount(1, $featureBox);
    }
    
    /**
     * @dataProvider providerIsEnabledWithProfile
     * @param array $input
     * @param array $expected
     */
    public function testIsEnabledWithProfile(array $input, array $expected)
    {
        $profile = new Profile();
        $featureBox = $this->createFeatureBox([], null, $profile);
        $featureBox->addFeature($input['feature']);

        $this->assertInternalType('array', $profile->getData());
        $this->assertEmpty($profile->getData());

        $isEnabled = $featureBox->isEnabled((string) $input['feature']);

        $this->assertEquals($expected['is_enabled'], $isEnabled);
        $this->assertEquals($expected['data'], $profile->getData());
    }
    
    /**
     * @return array
     */
    public function providerIsEnabledWithProfile()
    {
        $enabledFeature = new Feature('enabled-feature');
        $enabledFeature->addCondition(new BoolCondition(true));

        $disabledFeatureWithParent = new Feature('disabled-feature', null, $enabledFeature);
        $disabledFeatureWithParent->addCondition(new BoolCondition(false));

        return [
            'enabled-feature-without-parent' => [
                [ 'feature' => $enabledFeature ],
                [
                    'is_enabled' => true,
                    'data' => [
                        'enabled-feature' => [
                            'name' => 'enabled-feature',
                            'exists' => true,
                            'parent' => '',
                            'is_enabled' => true,
                            'count' => 1
                        ]
                    ]
                ]
            ],
            'disabled-feature-with-parent' => [
                [ 'feature' => $disabledFeatureWithParent ],
                [
                    'is_enabled' => false,
                    'data' => [
                        'disabled-feature' => [
                            'name' => 'disabled-feature',
                            'exists' => true,
                            'parent' => 'enabled-feature',
                            'is_enabled' => false,
                            'count' => 1
                        ]
                    ]
                ]
            ],
        ];
    }

    public function testHas()
    {
        $name = 'awesome-feature-' . rand(0, 9999);

        $featureBox = $this->createFeatureBox();
        $this->assertFalse($featureBox->has($name));

        $featureBox->addFeature(new Feature($name));
        $this->assertTrue($featureBox->has($name));
    }

    /**
     * Factorymethod for FeatureBox-objects
     * 
     * @param type $features
     * @param ContextInterface $context
     * @param ProfileInterface $profile
     * @param array $options
     * @return FeatureBox
     */
    protected function createFeatureBox(
        $features = [], 
        ContextInterface $context = null,
        ProfileInterface $profile = null,
        array $options = []
    ) {
        if (null === $context) {
            $context = new Context();
        }

        return new FeatureBox($features, $context, $profile, $options);
    }
}
