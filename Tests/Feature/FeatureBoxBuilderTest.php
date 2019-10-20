<?php

namespace E7\FeatureFlagsBundle\Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Class FeatureBoxBuilderTest
 * @package E7\FeatureFlagsBundle\Tests\Feature
 */
class FeatureBoxBuilderTest extends TestCase
{
    public function testBuildFromConfig()
    {
        $config = [
            'default' => true, /* should be optional */
            'features' => [
                'foo',
                'bar' => false,
                'bazz' => [
                    'type' => 'ipaddress',
                    'ips' => '127.0.0.1',
                ],
                'bazzClass' => [
                    'type' => \E7\FeatureFlagsBundle\Feature\Conditions\IpAddressCondition::class,
                    'ips' => '127.0.0.1',
                ],
                'bazzType' => [
                    'type' => 'ipaddress',
                    'ips' => '192.168.0.1',
                ],
                'bazzBoth' => [
                    'type' => 'ipaddress',
                    'class' => \E7\FeatureFlagsBundle\Feature\Conditions\IpAddressCondition::class,
                    'ips' => '127.0.0.1',
                ],
                'bamm' => [ 'onlysub' ],
            ],
            'conditions' => [
                'onlysub' => [
                    'type' => 'host',
                    'hostnames' => '*.example.com',
                ]
            ],
        ];
        
        $builder = new \E7\FeatureFlagsBundle\Feature\FeatureBoxBuilder();
        $box = $builder->buildFromConfig($config);
        
//        print_r($box);
        
        
        $this->assertTrue(true);
        /*
 * e7_feature_flags:
 *      default: true
 *      features:
 *          foo: true
 *          bar: true
 *          bazz: [onlysub]
 *          bamm: onlysub
 *      conditions:
 *          onlysub:
 *              type[/class]: host
 *              hostnames: blog.example.com
 */
    }
}
