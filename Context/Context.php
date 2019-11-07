<?php

namespace E7\FeatureFlagsBundle\Context;

use E7\FeatureFlagsBundle\Context\Key;
use E7\FeatureFlagsBundle\Context\Provider\ProviderInterface;

/**
 * Class Context
 * @package E7\FeatureFlagsBundle\Context
 */
class Context implements ContextInterface
{
    /** @var array */
    private $data = [];

    /**
     * Constructor
     *
     * @param $providers
     */
    public function __construct($providers = [])
    {
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * Add provider to all claimed keys
     *
     * @param ProviderInterface $provider
     * @return Context
     */
    public function addProvider(ProviderInterface $provider)
    {
        foreach ($provider->getClaimedKeys() as $key) {
            $key = new Key($key);
            $this->data[$key->getRoot()] = $provider;
        }

        return $this;
    }

    /**
     * Remove provider and all claimed keys
     *
     * @param ProviderInterface $provider
     * @return Context
     */
    public function removeProvider(ProviderInterface $provider)
    {
        return call_user_func_array([$this, 'remove'], $provider->getClaimedKeys());
    }

    /**
     * Set data
     *
     * @param array $data
     * @return Context
     */
    public function setData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value = null)
    {
        $key = new Key($key);
        $this->data[(string) $key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        $value = null;
        $key = new Key($key);

        if (!empty($this->data[(string) $key])) {
            $value = $this->data[(string) $key];
        } elseif (!empty($this->data[$key->getRoot()])) {
            $value = $this->data[$key->getRoot()];
        } else {
            $value = $default;
        }

        return $value instanceof ProviderInterface
            ? $value->get($key, $default) : $value;
    }

    /**
     * @inheritDoc
     */
    public function has(string $key)
    {
        $key = new Key($key);

        if (!empty($this->data[(string) $key])) {
            return true;
        }

        if (!empty($this->data[$key->getRoot()])) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function remove(string ...$key)
    {
        foreach ($key as $k) {
            $k = new Key($k);

            if (!empty($this->data[$k->getRoot()])) {
                $value = $this->data[$k->getRoot()];

                if ($value instanceof ProviderInterface) {
                    foreach ($value->getClaimedKeys() as $k2) {
                        $k2 = new Key($k2);
                        unset($this->data[(string) $k2]);
                    }
                    return call_user_func_array([$this, 'remove'], $value->getClaimedKeys());
                }
            }

            unset($this->data[(string) $k]);
        }

        return $this;
    }
}
