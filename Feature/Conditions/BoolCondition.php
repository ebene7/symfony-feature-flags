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
     * @param string $name
     */
    public function __construct($flag, string $name = null)
    {
        parent::__construct($name);

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
