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
    public function getName(): string
    {
        return 'feature-flags';
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
        $numMissing = 0;

        foreach ($this->box as $name => $feature) {
            $features[$name] = [
                'name' => $name,
                'parent' => null !== $feature->getParent() ? $feature->getParent()->getName() : null,
                'conditions' => implode(', ', iterator_to_array($feature->getConditions())),
            ];
        }

        $data = [
            'feature_count' => count($this->box),
            'hits_count' => count($profile->getData()),
            'missing_count' => $profile->countMissingFeatures(),
            'features' => $features,
            'hits' => $profile->getData(),
        ];

        $this->data = $data;
    }

    public function reset()
    {
        $this->data = [];
    }

    /**
     * @return integer
     */
    public function getFeatureCount()
    {
        return !empty($this->data['feature_count'])
            ? (int) $this->data['feature_count'] : 0;
    }

    /**
     * @return integer
     */
    public function getHitCount()
    {
        return !empty($this->data['hits_count'])
            ? (int) $this->data['hits_count'] : 0;
    }

    /**
     * @return integer
     */
    public function getMissingCount()
    {
        return !empty($this->data['missing_count'])
            ? (int) $this->data['missing_count'] : 0;
    }

    /**
     * @return array
     */
    public function getHits()
    {
        return !empty($this->data['hits']) ? $this->data['hits'] : [];
    }

    /**
     * @return array
     */
    public function getFeatures()
    {
        return !empty($this->data['features']) ? $this->data['features'] : [];
    }
}
