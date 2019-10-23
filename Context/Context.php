<?php

namespace E7\FeatureFlagsBundle\Context;

use E7\FeatureFlagsBundle\Context\Provider\ProviderInterface;

/**
 * Class Context
 */
class Context implements ContextInterface
{
    /** @var array */
    private $data = [];

    /**
     * Constructor
     * 
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value)
    {
        $key = $this->normalizeKey($key);
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        $value = !empty($this->data[$key]) ? $this->data[$key] : $default;
        
        return $value instanceof ProviderInterface
            ? $value->get($key)
            : $value;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key)
    {
        $key = $this->normalizeKey($key);
        return !empty($this->data[$key]);
    }

    /**
     * @inheritDoc
     */
    public function remove(string ...$key)
    {
        foreach ($key as $k) {
            $k = $this->normalizeKey($k);
            unset($this->data[$k]);
        }

        return $this;
    }

    /**
     * Normalize $key value
     * 
     * @param string $key
     * @return string Normalized $key
     */
    protected function normalizeKey(string $key): string
    {
        return strtolower($key);
    }
}
