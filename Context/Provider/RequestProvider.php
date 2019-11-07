<?php

namespace E7\FeatureFlagsBundle\Context\Provider;

use E7\FeatureFlagsBundle\Context\Key;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestProvider
 * @package E7\FeatureFlagsBundle\Context\Provider
 */
class RequestProvider implements ProviderInterface
{
    /** @var Request */
    private $request;

    /**
     * Constructor
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getClaimedKeys(): array
    {
        return [ 'request' ];
    }

    /**
     * @inheritDoc
     */
    public function get(Key $key, $default = null)
    {
        if (!$key->hasPath()) {
            return $this->request;
        }

        // TODO: make it more dynamic
        switch($key->getPath()) {
            case 'client_ip':
                return $this->request->getClientIp();
            case 'host':
                return $this->request->getHost();
            case 'method':
                return $this->request->getMethod();
        }
    }
}
