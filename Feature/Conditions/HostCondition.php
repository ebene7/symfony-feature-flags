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
    private $hostnames;
    
    /**
     * Constructor
     * 
     * @param array|string $hostnames
     * @throws InvalidArgumentException
     */
    public function __construct($hostnames)
    {
        $this->hostnames = is_string($hostnames) ? [$hostnames] : $hostnames;

        if (!is_array($this->hostnames)) {
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

    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        //$hostname = $context->get('hostname');

        foreach ($this->hostnames as $hostname) {
            $pattern = '/' . str_replace('*', '(.*)', $hostname) . '/';

            preg_match($pattern, $context->get('hostname'));
        }


    }
}
