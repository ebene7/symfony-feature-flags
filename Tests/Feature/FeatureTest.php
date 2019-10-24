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








class Collector {
    private $data = [];
    public function __invoke() {  /*file_put_contents(sys_get_temp_dir() . '/a', json_encode(func_get_args()), FILE_APPEND); echo 'HIER';*/ }
    public function add() { /*file_put_contents(sys_get_temp_dir() . '/a', json_encode(func_get_args()), FILE_APPEND); */}
    public function getData() {/* return '##'.file_get_contents(sys_get_temp_dir() . '/a');*/ }
}
//
// https://www.php.net/manual/en/class.streamwrapper.php
//
//class ArrayStreamWrapper
//{
//    private $data = [];
//    public $context;
//
//    public function __construct () {}
//    public function __destruct () {}
//    public function dir_closedir () : bool {}
//    public function dir_opendir (string $path, int $options) : bool {}
//    public function dir_readdir () : string {}
//    public function dir_rewinddir () : bool {}
//    public function mkdir (string $path, int $mode, int $options) : bool {}
//    public function rename (string $path_from , string $path_to) : bool {}
//    public function rmdir (string $path , int $options) : bool {}
//    public function stream_cast (int $cast_as) : resource {}
//    public function stream_close () {}
//    public function stream_eof () : bool {}
//    public function stream_flush () : bool {}
//    public function stream_lock (int $operation ) : bool {}
//    public function stream_metadata (string $path , int $option , mixed $value ) : bool {}
//    public function stream_open (string $path , string $mode , int $options , string &$opened_path ) : bool {}
//    public function stream_read (int $count ) : string {}
//    public function stream_seek (int $offset , int $whence = SEEK_SET ) : bool {}
//    public function stream_set_option (int $option , int $arg1 , int $arg2 ) : bool {}
//    public function stream_stat () : array {}
//    public function stream_tell () : int {}
//    public function stream_truncate (int $new_size) : bool {}
//    public function stream_write (string $data) : int {}
//    public function unlink (string $path) : bool {}
//    public function url_stat (string $path , int $flags) : array {}
//}