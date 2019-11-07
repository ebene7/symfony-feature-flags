<?php

namespace E7\FeatureFlagsBundle\Context\Provider;

use E7\FeatureFlagsBundle\Context\Key;

/**
 * Interface ProviderInterface
 */
interface ProviderInterface
{
    /**
     * Get keys to register
     *
     * @return array
     */
    public function getClaimedKeys(): array;

    /**
     * Get Value
     *
     * @param Key $key
     * @param mixed $default
     * @return mixed
     */
    public function get(Key $key, $default = null);
}
