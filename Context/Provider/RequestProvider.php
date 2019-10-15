<?php

namespace E7\FeatureFlagsBundle\Context\Provider;

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
    public function __construct($request)
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
