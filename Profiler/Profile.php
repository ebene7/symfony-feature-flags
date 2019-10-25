<?php

namespace E7\FeatureFlagsBundle\Profiler;

use E7\FeatureFlagsBundle\Feature\FeatureInterface;

/**
 * Class Profile
 * @package E7\FeatureFlagsBundle\Profiler
 */
class Profile implements ProfileInterface
{
    /** @var array */
    private $data = [];

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function hit(
        string $name,
        bool $isEnabled,
        FeatureInterface $feature = null
    ): ProfileInterface {
        $data = &$this->data;
        echo '##';
        if (empty($data[$name])) {
            $data[$name] = [
                'name' => $name,
                'exists' => null !== $feature,
                'parent' => null !== $feature ? $feature->getParent()->getName() : '',
                'is_enabled' => $isEnabled,
                'count' => 0,
            ];
        }

        $data[$name]['count']++;

        return $this;
    }
}
