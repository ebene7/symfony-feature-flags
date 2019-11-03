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
     * @parame string $name
     */
    public function __construct(string $featureName, string $name = null)
    {
        parent::__construct($name);

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
        $pattern = '/^' . str_replace('*', '(.*)', $this->featureName) . '$/';
        return (bool) preg_match($pattern, $context->get('feature')->getName());
    }
}