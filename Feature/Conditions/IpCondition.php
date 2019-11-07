<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;
use InvalidArgumentException;

/**
 * Class IpCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class IpCondition extends AbstractCondition
{
    const PATTERN_V4 = '/\d{1,3}|\*\.\d{1,3}|\*\.\d{1,3}|\*\.\d{1,3}|\*/';
    const PATTERN_V6 = '/[a-f0-9]{0,4}\:[a-f0-9]{0,4}\:[a-f0-9]{0,4}\:[a-f0-9]{0,4}\:'
                     . '[a-f0-9]{0,4}\:[a-f0-9]{0,4}\:[a-f0-9]{0,4}\:[a-f0-9]{0,4}/';
    
    /** @var array */
    private $ips = [];
    
    /**
     * Constructor
     *
     * @param array|string $ips
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function __construct($ips, string $name = null)
    {
        parent::__construct($name);

        if (!is_array($ips)) {
            $ips = [$ips];
        }

        foreach ($ips as $ip) {
            $ip = $this->normalize($ip);
            
            if (false === (bool) preg_match(self::PATTERN_V4, $ip)
                && false === (bool) preg_match(self::PATTERN_V6, $ip)
                && !is_long($ip)) {
                throw new InvalidArgumentException('Value (' . $ip . ') is not a valid ip address');
            }
            
            $this->ips[] = $ip;
        }
    }
    
    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'ip';
    }

    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        $clientIp = $this->normalize($context->get('request.client_ip'));
        
        foreach ($this->ips as $ip) {
            $pattern = '/' . str_replace(['.', '*'], ['\\.','(.*)'], $ip) . '/i';

            if ((bool) preg_match($pattern, $clientIp)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $ip
     * @return mixed
     */
    protected function normalize($ip)
    {
        if (is_long($ip)) {
            $ip = long2ip($ip);
        }
        return $ip;
    }
}
