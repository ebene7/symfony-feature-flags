<?php

namespace E7\FeatureFlagsBundle\Context;

/**
 * Interface ContextInterface
 */
interface ContextInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @return ContextInterface
     */
    public function set($key, $value = null);

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @return boolean
     */
    public function has(string $key);

    /**
     * @param string $key
     * @return ContextInterface
     */
    public function remove(string ...$key);
}
