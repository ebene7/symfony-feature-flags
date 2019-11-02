<?php

namespace E7\FeatureFlagsBundle\Tests\Profiler;

use E7\FeatureFlagsBundle\Feature\Feature;
use E7\FeatureFlagsBundle\Profiler\Profile;

/**
 * Class ProfileTest
 * @package E7\FeatureFlagsBundle\Tests\Profiler
 */
class ProfileTest extends ProfileTestCase
{
    public function testHit()
    {
        $profile = new Profile();
        $this->doTestHit($profile);
    }

    /**
     * @dataProvider providerHitDataGeneration
     * @param array $input
     * @param array $expected
     */
    public function testHitDataGeneration(array $input, array $expected)
    {
        // prepare
        $profile = new Profile();
        $parent = !empty($input['parent'])
            ? new Feature($input['parent']) : null;
        $feature = $input['exists'] 
            ? new Feature($input['name'], null, $parent) : null;
        
        // execute
        $profile->hit($input['name'], $input['is_enabled'], $feature);
        
        // test
        $this->assertEquals($expected['data'], $profile->getData());
    }
    
    public function providerHitDataGeneration()
    {
        return [
            'feature-exists-without-parent' => [
                [
                    'name' => 'awesome-feature',
                    'exists' => true,
                    'is_enabled' => true,
                    'parent' => null,
                ],
                [
                    'data' => [
                        'awesome-feature' => [
                            'name' => 'awesome-feature',
                            'exists' => true,
                            'parent' => '',
                            'is_enabled' => true,
                            'count' => 1,
                        ]
                    ]
                ]
            ],
            'feature-exists-with-parent' => [
                [
                    'name' => 'awesome-feature',
                    'exists' => true,
                    'is_enabled' => true,
                    'parent' => 'awesome-feature-parent',
                ],
                [
                    'data' => [
                        'awesome-feature' => [
                            'name' => 'awesome-feature',
                            'exists' => true,
                            'parent' => 'awesome-feature-parent',
                            'is_enabled' => true,
                            'count' => 1,
                        ]
                    ]
                ]
            ],
            'feature-does-not-exists' => [
                [
                    'name' => 'awesome-feature',
                    'exists' => false,
                    'is_enabled' => true,
                    'parent' => 'awesome-feature-parent',
                ],
                [
                    'data' => [
                        'awesome-feature' => [
                            'name' => 'awesome-feature',
                            'exists' => false,
                            'parent' => '',
                            'is_enabled' => true,
                            'count' => 1,
                        ]
                    ]
                ]
            ],
        ];
    }
    
    public function testGetData()
    {
        $profile = new Profile();
        
        $this->assertObjectHasMethod('getData', $profile);
        $this->assertInternalType('array', $profile->getData());
    }

    public function testCountMissingFeatures()
    {
        $profile = new Profile();

        $this->assertObjectHasMethod('countMissingFeatures', $profile);

        $countBefore = $profile->countMissingFeatures();

        $this->assertInternalType('integer', $countBefore);
        $this->assertEquals(0, $countBefore);

        $profile->hit('not-configured-feature', false);

        $countAfter = $profile->countMissingFeatures();

        $this->assertInternalType('integer', $countAfter);
        $this->assertEquals(1, $countAfter);
    }
}
