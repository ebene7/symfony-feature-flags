<?php

namespace E7\FeatureFlagsBundle\Tests\Feature;

use ArrayIterator;
use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Context\ContextInterface;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionFactory;
use E7\FeatureFlagsBundle\Feature\Feature;
use E7\FeatureFlagsBundle\Feature\FeatureBox;
use PHPUnit\Framework\TestCase;
use E7\PHPUnit\Traits\OopTrait;

/**
 * Class FeatureBoxTest
 * @package E7\FeatureFlagsBundle\Tests\Feature
 */
class FeatureBoxTest extends TestCase
{
    use OopTrait;
    
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
     * Factorymethod for FeatureBox-objects
     * 
     * @param type $features
     * @param ContextInterface $context
     * @param array $options
     * @return FeatureBox
     */
    protected function createFeatureBox(
        $features = [], 
        ContextInterface $context = null,
        array $options = []
    ) {
        if (null === $context) {
            $context = new Context();
        }
        
        return new FeatureBox($features, $context, $options);
    }
}
