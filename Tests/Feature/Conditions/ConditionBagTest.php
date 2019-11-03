<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Feature\Conditions\BoolCondition;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionBag;
use E7\PHPUnit\Traits\OopTrait;
use E7\PHPUnit\Traits\ValuesTrait;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class ConditionBagTest
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class ConditionBagTest extends TestCase
{
    use OopTrait;
    use ValuesTrait;

    public function testConstructor()
    {
        $reflection = new ReflectionClass(ConditionBag::class);

        $this->assertTrue($reflection->hasMethod('__construct'));

        $constructor = $reflection->getConstructor();

        $this->assertEquals(1, $constructor->getNumberOfParameters());
        $this->assertEquals(0, $constructor->getNumberOfRequiredParameters());
    }

    public function testAdd()
    {
        // prepare
        $name = $this->getRandomName();
        $condition = new BoolCondition(true, $name);
        $bag = new ConditionBag();

        // execute & test
        $this->assertCount(0, $bag);
        $this->assertSame($bag, $bag->add($condition));
        $this->assertCount(1, $bag);
    }

    /**
     * @dataProvider providerSet
     * @param array $input
     * @param array $excepted
     */
    public function testSet(array $input, array $excepted)
    {
        // prepare
        $condition = new BoolCondition(true, $input['condition_name']);

        $bag = is_bool($input['allow_override_defaults'])
            ? new ConditionBag($input['allow_override_defaults'])
            : new ConditionBag();

        if (!empty($excepted['exception'])) {
            $this->expectException($excepted['exception']);
        }

        // execute & test
        $this->assertCount(0, $bag);
        $this->assertSame($bag, $bag->set($input['condition_name'], $condition));
        $this->assertCount(1, $bag);

        // test override
        $this->assertSame($bag, $bag->set($input['condition_name'], $condition));
    }

    /**
     * @return array
     */
    public function providerSet()
    {
        $name = $this->getRandomName();

        return [
            'default-without-override-param-and-default-condition' => [
                [
                    'allow_override_defaults' => null,
                    'condition_name' => 'enabled',
                ],
                [ 'exception' => Exception::class ]
            ],
            'default-without-override-param-and-non-default-condition' => [
                [
                    'allow_override_defaults' => null,
                    'condition_name' => $name,
                ],
                [ 'exception' => null ]
            ],
            'default-with-false-override-param-and-default-condition' => [
                [
                    'allow_override_defaults' => false,
                    'condition_name' => 'enabled',
                ],
                [ 'exception' => Exception::class ]
            ],
            'default-with-false-override-param-and-non-default-condition' => [
                [
                    'allow_override_defaults' => false,
                    'condition_name' => $name,
                ],
                [ 'exception' => null ]
            ],
            'default-with-true-override-param-and-default-condition' => [
                [
                    'allow_override_defaults' => true,
                    'condition_name' => 'enabled',
                ],
                [ 'exception' => null ]
            ],
            'default-with-true-override-param-and-non-default-condition' => [
                [
                    'allow_override_defaults' => true,
                    'condition_name' => $name,
                ],
                [ 'exception' => null ]
            ],
        ];
    }

    public function testGet()
    {
        // prepare
        $name = $this->getRandomName();
        $condition = new BoolCondition(true, $name);
        $bag = new ConditionBag();
        $bag->add($condition);

        // test
        $this->assertSame($condition, $bag->get($name));
    }

    public function testHas()
    {
        // prepare
        $name = $this->getRandomName();
        $condition = new BoolCondition(true, $name);
        $bag = new ConditionBag();
        $bag->add($condition);

        // test
        $this->assertTrue($bag->has($name));
    }
    
    public function testAll()
    {
        // prepare
        $name = $this->getRandomName();
        $condition = new BoolCondition(true, $name);
        $bag = new ConditionBag();

        // test
        $all = $bag->all();
        $this->assertInternalType('array', $all);
        $this->assertEmpty($all);

        $bag->add($condition);
        $this->assertTrue($bag->has($name));

        $all2 = $bag->all();
        $this->assertInternalType('array', $all2);
        $this->assertCount(1, $all2);
    }

    public function testSplIteratorAggregate()
    {
        $this->doTestSplIteratorAggregateImplementation(new ConditionBag());
    }

    public function testSplCountable()
    {
        $this->doTestSplCountableImplementation(new ConditionBag());
    }
}
