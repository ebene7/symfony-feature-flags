<?php

namespace E7\FeatureFlagsBundle\Tests\Context;

use E7\FeatureFlagsBundle\Context\Key;
use PHPUnit\Framework\TestCase;

/**
 * Class KeyTest
 * @package E7\FeatureFlagsBundle\Tests\Context
 */
class KeyTest extends TestCase
{
    /**
     * @dataProvider providerConstructorAndGetter
     * @param array $input
     * @param array $expected
     */
    public function testConstructorAndGetter(array $input, array $expected)
    {
        $key = new Key($input['key'], $input['separator']);
        
        $this->assertEquals($expected['key'], (string) $key);
        $this->assertEquals($expected['key'], $key->getKey());
        $this->assertEquals($expected['root'], $key->getRoot());
        $this->assertEquals($expected['path'], $key->getPath());
        $this->assertInternalType('bool', $key->hasPath());
        $this->assertEquals($expected['has_path'], $key->hasPath());
    }
    
    /**
     * @return array
     */
    public function providerConstructorAndGetter()
    {
        return [
            'only_root_key' => [
                [ 'key' => 'onlyroot', 'separator' => null ],
                [
                    'key' => 'onlyroot',
                    'root' => 'onlyroot',
                    'path' => '',
                    'has_path' => false,
                ]
            ],
            'empty_key' => [
                [ 'key' => '', 'separator' => null ],
                [
                    'key' => '',
                    'root' => '',
                    'path' => '',
                    'has_path' => false,
                ]
            ],
            'null_key' => [
                [ 'key' => '', 'separator' => null ],
                [
                    'key' => '',
                    'root' => '',
                    'path' => '',
                    'has_path' => false,
                ]
            ],
            'number_key' => [
                [ 'key' => 42, 'separator' => null ],
                [
                    'key' => '42',
                    'root' => '42',
                    'path' => '',
                    'has_path' => false,
                ]
            ],
            'float_key' => [
                [ 'key' => 42.666, 'separator' => null ],
                [
                    'key' => '42.666',
                    'root' => '42',
                    'path' => '666',
                    'has_path' => true,
                ]
            ],
            'key_with_path_and_default_separator' => [
                [ 'key' => 'root.path.part1.part2', 'separator' => null ],
                [
                    'key' => 'root.path.part1.part2',
                    'root' => 'root',
                    'path' => 'path.part1.part2',
                    'has_path' => true,
                ]
            ],
            'key_with_path_and_custom_separator' => [
                [ 'key' => 'root-path-part1-part2', 'separator' => '-' ],
                [
                    'key' => 'root-path-part1-part2',
                    'root' => 'root',
                    'path' => 'path-part1-part2',
                    'has_path' => true,
                ]
            ],
        ];
    }
}
