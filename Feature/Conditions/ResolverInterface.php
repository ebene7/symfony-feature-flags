<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

/**
 * Interface ResolverInterface
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
interface ResolverInterface
{
    /**
     * @param string $type
     * @return string|null
     */
    public function resolve(string $type): string;
}