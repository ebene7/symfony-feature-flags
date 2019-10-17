<?php

namespace E7\FeatureFlagsBundle\Context\Provider;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestProvider
 */
class RequestProvider implements ProviderInterface
{
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
    public function getName(): string
    {
        return 'request';
    }
}
