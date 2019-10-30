<?php

namespace E7\FeatureFlagsBundle\Tests\Feature;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionFactory;
use E7\FeatureFlagsBundle\Feature\Conditions\TypeResolver;
use E7\FeatureFlagsBundle\Feature\FeatureBox;
use E7\FeatureFlagsBundle\Feature\FeatureBoxBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureBoxBuilderTest
 * @package E7\FeatureFlagsBundle\Tests\Feature
 */
class FeatureBoxBuilderTest extends TestCase
{
    /**
     * @dataProvider providerBuildFromConfig
     * @param array $input
     * @param array $expected
     */
    public function testBuildFromConfig(array $input, array $expected)
    {
        $factory = new ConditionFactory(new TypeResolver());
        $builder = new FeatureBoxBuilder($factory);
        $box = $builder->buildFromConfig($input['config']);

        $this->assertInstanceOf(FeatureBox::class, $box);
        $this->assertCount(count($expected['features']), $box);

        $findex = 0;
        foreach ($box as $feature) {
            $fe = $expected['features'][$findex++];

            $this->assertEquals($fe['name'], $feature->getName());
            $this->assertEquals($fe['is_enabled'], $feature->isEnabled($input['context']));
            $this->assertCount(count($fe['conditions']), $feature->getConditions());

            $cindex = 0;
            foreach ($feature->getConditions() as $condition) {
                $ce = $fe['conditions'][$cindex++];

                $this->assertEquals($ce['type'], $condition->getType());
                $this->assertEquals($ce['name'], $condition->getName());
            }
        }
    }

    /**
     * @return array
     */
    public function providerBuildFromConfig()
    {
        return [
            'key-name-only-bool' => [
                [
                    'config' => [
                        'features' => [
                            'awesome-feature1' => true,
                        ]
                    ],
                    'context' => new Context(),
                ],
                [
                    'features' => [
                        [
                            'name' => 'awesome-feature1',
                            'is_enabled' => true,
                            'conditions' => [
                                [ 'type' => 'bool', 'name' => ''],
                            ]
                        ]
                    ]
                ]
            ],
            'key-name-with-enabled-true' => [
                [
                    'config' => [
                        'features' => [
                            'awesome-feature2' => [
                                'enabled' => true,
                            ]
                        ]
                    ],
                    'context' => new Context(),
                ],
                [
                    'features' => [
                        [
                            'name' => 'awesome-feature2',
                            'is_enabled' => true,
                            'conditions' => [
                                [ 'type' => 'bool', 'name' => '' ],
                            ]
                        ]
                    ]
                ]
            ],
            'key-name-with-condition-string' => [
                [
                    'config' => [
                        'features' => [
                            'awesome-feature3' => 'condition1',
                        ],
                        'conditions' => [
                            'condition1' => [
                                'type' => 'host',
                                'hosts' => '*.example.com',
                            ]
                        ]
                    ],
                    'context' => new Context(['host' => 'www.example.com']),
                ],
                [
                    'features' => [
                        [
                            'name' => 'awesome-feature3',
                            'is_enabled' => true,
                            'conditions' => [
                                [ 'type' => 'host', 'name' => 'condition1' ],
                            ]
                        ]
                    ]
                ]
            ],
            'key-name-with-condition-array' => [
                [
                    'config' => [
                        'features' => [
                            'awesome-feature4' => [
                                'condition2',
                                'condition3',
                            ],
                        ],
                        'conditions' => [
                            'condition2' => [
                                'type' => 'host',
                                'hosts' => '*.example.com',
                            ],
                            'condition3' => [
                                'type' => 'ip',
                                'ips' => '192.168.0.*',
                            ],
                        ]
                    ],
                    'context' => new Context([
                        'host' => 'www.example.com',
                        'client_ip' => '192.168.0.100',
                    ]),
                ],
                [
                    'features' => [
                        [
                            'name' => 'awesome-feature4',
                            'is_enabled' => true,
                            'conditions' => [
                                [ 'type' => 'host', 'name' => 'condition2' ],
                                [ 'type' => 'ip', 'name' => 'condition3' ],
                            ]
                        ]
                    ]
                ]
            ],            
        ];
    }
}
