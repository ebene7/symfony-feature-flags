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
     * @throws InvalidArgumentException
     */
    public function __construct($hosts)
    {
        $this->hosts = is_string($hosts) ? [$hosts] : $hosts;

        if (!is_array($this->hosts)) {
            echo '#' . gettype($this->hosts) . '#';
            throw new InvalidArgumentException();
        }
    }
    
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'host';
    }

    protected function doVote(ContextInterface $context)
    {
        
    }
}
