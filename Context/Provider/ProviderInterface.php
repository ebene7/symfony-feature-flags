<?php

namespace E7\FeatureFlagsBundle\Context\Provider;

/**
 * Interface ProviderInterface
 */
interface ProviderInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}
