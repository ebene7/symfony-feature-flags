<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Context\ContextInterface;

/**
 * Class FeatureCondition
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class FeatureCondition extends AbstractCondition
{
    /** @var string */
    private $featureName;

    /**
     * FeatureCondition constructor.
     * @param string $featureName
     */
    public function __construct(string $featureName)
    {
        $this->featureName = $featureName;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'feature';
    }

    /**
     * @inheritDoc
     */
    protected function doVote(ContextInterface $context)
    {
        $pattern = '/' . str_replace('*', '(.*)', $this->featureName) . '/';
        $r =  (bool) preg_match($pattern, $context->get('feature')->getName(), $match);

        echo "TN=" . $this->featureName . " CN=" . $context->get('feature')->getName() . ' PAT=' . $pattern;
        print_r($match);

        return $r;
    }
}