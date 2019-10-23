<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;
use InvalidArgumentException;

/**
 * Class CallbackCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class CallbackCondition extends AbstractCondition
{
    /** @var callable */
    private $callback;
    
    /**
     * Constructor
     * 
     * @param callable $callback
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('First parameter must be callable.');
        }
        
        $this->callback = $callback;
    }
    
    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'callback';
    }
    
    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        return call_user_func($this->callback, $context);
    }
}
