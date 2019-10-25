<?php

namespace E7\FeatureFlagsBundle\DataCollector;

use E7\FeatureFlagsBundle\Feature\FeatureBox;
use Exception;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FeatureCollector
 * @package E7\FeatureFlagsBundle\DataCollector
 */
class FeatureCollector extends DataCollector
{
    /** @var FeatureBox */
    private $box;

    public function __construct(FeatureBox $box)
    {
        $this->box = $box;
    }

    /**
     * @inheritDoc
     */
    public function collect(
        Request $request,
        Response $response,
        Exception $exception = null
    ) {
        $profile = $this->box->getProfile();
        $features = [];

        foreach ($this->box as $name => $feature) {
            $features[$name] = [
                'name' => $name,
                'parent' => null !== $feature->getParent() ? $feature->getParent()->getName() : '',
                'conditions' => implode(', ', iterator_to_array($feature->getConditions())),
            ];
        }

        $data = [
            'feature_count' => count($this->box),
            'hits_count' => count($profile->getData()),
            'features' => $features,
            'hits' => $profile->getData(),
        ];

        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'e7.feature_flags_bundle.collector';
    }

    /**
     * @return integer
     */
    public function getFeatureCount()
    {
        return $this->data['feature_count'];
    }

    /**
     * @return array
     */
    public function getHits()
    {
        return $this->data['hits'];
    }

    /**
     * @return array
     */
    public function getFeatures()
    {
        return $this->data['features'];
    }
}
