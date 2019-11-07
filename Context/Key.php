<?php

namespace E7\FeatureFlagsBundle\Context;

/**
 * Class Key
 * @package E7\FeatureFlagsBundle\Context
 */
class Key
{
    /** @var string */
    private $key;
    
    /** @var string */
    private $rootKey;
    
    /** @var string */
    private $path;
    
    /**
     * Constructor
     * 
     * @param string $key
     * @param string $separator
     */
    public function __construct(string $key, $separator = null)
    {
        if (null === $separator) {
            $separator = '.';
        }
        
        $this->key = strtolower($key);
        $pieces = explode($separator, $this->key);
        $this->rootKey = trim(array_shift($pieces));
        $this->path = trim(implode($separator, $pieces));
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getKey();
    }
    
    /**
     * @return bool
     */
    public function hasPath(): bool
    {
        return !empty($this->path);
    }
    
    /**
     * Get key
     * 
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get root key
     * 
     * @return string
     */
    public function getRoot(): string
    {
        return $this->rootKey;
    }

    /**
     * Get path
     * 
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


}
