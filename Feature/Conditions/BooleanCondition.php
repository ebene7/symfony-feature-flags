<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Class BooleanCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class BooleanCondition extends AbstractCondition
{
    /** @var boolean */
    private $flag;

    /**
     * Construct
     *
     * @param boolean $flag
     */
    public function __construct($flag)
    {
        $this->flag = (bool) $flag;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'boolean';
    }

    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        return $this->flag;
    }
}
