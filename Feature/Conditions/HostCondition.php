<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;
use InvalidArgumentException;

/**
 * Class HostCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class HostCondition extends AbstractCondition
{
    /** @var array */
    private $hosts;
    
    /**
     * Constructor
     *
     * @param array|string $hosts
     * @param string $name
     * @throws InvalidArgumentException
     */
    public function __construct($hosts, string $name = null)
    {
        parent::__construct($name);

        $this->hosts = is_string($hosts) ? [$hosts] : $hosts;

        if (!is_array($this->hosts)) {
            throw new InvalidArgumentException();
        }
    }
    
    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'host';
    }

    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        foreach ($this->hosts as $host) {
            $pattern = '/' . str_replace('*', '(.*)', $host) . '/';

            if ((bool) preg_match($pattern, $context->get('request.host'))) {
                return true;
            }
        }

        return false;
    }
}
