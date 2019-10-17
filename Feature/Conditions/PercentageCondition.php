<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Class PercentageCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class PercentageCondition extends AbstractCondition
{
    private $percentage;

    /**
     * PercentageCondition constructor.
     * @param int $percentage
     */
    public function __construct(int $percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        $n = mt_rand(1, 100);

//        echo "n=$n p={$this->percentage} " . (($n <= $this->percentage) ? '#' : '') . "\n";

        return $n <= $this->percentage;
    }
}