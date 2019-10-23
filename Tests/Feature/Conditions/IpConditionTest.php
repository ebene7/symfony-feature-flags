<?php

namespace E7\FeatureFlagsBundle\Tests\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\IpCondition;
use InvalidArgumentException;

/**
 * Class IpAddressConditionTest
 *
 * @package E7\FeatureFlagsBundle\Tests\Feature\Conditions
 */
class IpConditionTest extends ConditionTestCase
{
    const IP_AS_LONG_192_168_1_0 = 3232235776;
    
    public function testMagicMethodToString()
    {
        $condition = new IpCondition('http://127.0.0.1');
        $this->doTestMagicMethodToString($condition);
        $this->doTestToStringConversion($condition);
    }

    public function testSetAndGetName()
    {
        $this->doTestGetterAndSetter(new IpCondition('192.168.0.*'), 'name');
    }

    /**
     * @dataProvider providerConstructorWithHostsParameter
     * @param array $input
     * @param array $expected
     */
    public function testConstructorWithException(array $input, array $expected)
    {
        if (null !== $expected['exception']) {
            $this->expectException($expected['exception']);
        }

        $this->assertInstanceOf(IpCondition::class, new IpCondition($input['ips']));
    }

    /**
     * @return array
     */
    public function providerConstructorWithHostsParameter()
    {
        return [
            'valid-string-parameter' => [
                [ 'ips' => '127.0.0.1' ],
                [ 'exception' => null ]
            ],
            'invalid-string-parameter' => [
                [ 'ips' => 'I.am.no.ip-address' ],
                [ 'exception' => InvalidArgumentException::class ]
            ],
            'valid-array-parameter' => [
                [ 'ips' => [ '127.0.0.1',  '192.169.1.*' ] ],
                [ 'exception' => null ]
            ],
            'invalid-array-parameter' => [
                [ 'ips' => [ 'I.am.no.ip-address',  '192.169.1.*' ] ],
                [ 'exception' => InvalidArgumentException::class ]
            ],
            'valid-number-parameter' => [
                [ 'ips' => self::IP_AS_LONG_192_168_1_0 ],
                [ 'exception' => null ]
            ],
            'invalid-number-parameter' => [
                [ 'ips' => self::IP_AS_LONG_192_168_1_0 * -1],
                [ 'exception' => null ]
            ],
        ];
    }
    
    /**
     * @dataProvider providerVote
     * @param array $input
     * @param array $expected
     */
    public function testVote(array $input, array $expected)
    {
        $condition = new IpCondition($input['ips']);
        $context = new Context(['client_ip' => $input['ip']]);

        $this->assertEquals($expected['match'], $condition->vote($context));
    }

    /**
     * @return array
     */
    public function providerVote()
    {
        return [
            'string-ipv4' => [
                [ 'ip' => '127.0.0.1', 'ips' => '127.0.0.1' ],
                [ 'match' => true ]
            ],
            'string-ipv6' => [
                [
                    'ip' => '2001:0db8:0000:0000:0000:ff00:0042:8329',
                    'ips' => '2001:0db8:0000:0000:0000:ff00:0042:8329'
                ],
                [ 'match' => true ]
            ],            
            'string-with-wildcard-v4' => [
                [ 'ip' => '192.169.1.1', 'ips' => '192.169.1.*' ],
                [ 'match' => true ]
            ],
            'string-with-wildcard-no-match-v4' => [
                [ 'ip' => '192.169.100.1', 'ips' => '192.169.1.*' ],
                [ 'match' => false ]
            ],
            'string-with-wildcard-v6' => [
                [
                    'ip' => '2001:0db8:0000:0000:0000:ff00:0042:8329',
                    'ips' => '2001:0db8:0000:0000:0000:ff00:0042:*'
                ],
                [ 'match' => true ]
            ],
            'string-with-wildcard-no-match-v6' => [
                [
                    'ip' => '2001:0db8:0000:0000:0000:ffff:ffff:8329',
                    'ips' => '2001:0db8:0000:0000:0000:ff00:0042:*'
                ],
                [ 'match' => false ]
            ],
            'number-parameter' => [
                [
                    'ip' => self::IP_AS_LONG_192_168_1_0,
                    'ips' => '192.168.1.*'
                ],
                [
                    'match' => true,
                ]
            ],
            'number-parameter-no-match' => [
                [
                    'ip' => self::IP_AS_LONG_192_168_1_0,
                    'ips' => '192.168.100.*'
                ],
                [
                    'match' => false,
                ]
            ],
        ];
    }
}
