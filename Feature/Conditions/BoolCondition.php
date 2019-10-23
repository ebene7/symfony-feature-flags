<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Class BoolCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class BoolCondition extends AbstractCondition
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
    public function getType(): string
    {
        return 'bool';
    }

    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        return $this->flag;
    }
}
