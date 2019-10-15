<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

class BooleanCondition extends AbstractCondition
{
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
    public function vote()
    {
        
    }

}
