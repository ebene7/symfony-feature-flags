<?php

namespace E7\FeatureFlagsBundle\Feature;

use E7\FeatureFlagsBundle\Context\Context;
use E7\FeatureFlagsBundle\Feature\Conditions\ConditionFactory;
use E7\FeatureFlagsBundle\Feature\FeatureBox;
use E7\FeatureFlagsBundle\Profiler\ProfileInterface;

/**
 * Class FeatureBoxBuilder
 * @package E7\FeatureFlagsBundle\Feature
 */
class FeatureBoxBuilder
{
    /** @var ConditionFactory */
    private $conditionFactory;

    /** @var ProfileInterface */
    private $profile;

    /**
     * Constructor
     *
     * @param ConditionFactory $factory
     * @param ProfileInterface $profile
     */
    public function __construct(ConditionFactory $factory, ProfileInterface $profile = null)
    {
        $this->conditionFactory = $factory;
        $this->profile = $profile;
    }

    /**
     * @param array $config
     * @return FeatureBoxInterface
     * @throws \ReflectionException
     */
    public function buildFromConfig(array $config)
    {
        $box = new FeatureBox([], new Context(), $this->profile);
        $box->setDefaultState(empty($config['default']) ? true : (bool) $config['default']);
        $factory = $this->conditionFactory;

        $features = [];

        $conditions = !empty($config['conditions']) 
            ? $this->prepareConditions($config['conditions']) 
            : [];

        foreach ($config['features'] as $key => $featureConfig) {

            if (is_string($key) && is_bool($featureConfig)) {
                $this->addFeatureWithFlag($box, $key, $featureConfig);
                continue;
            }

            if (is_string($key) && is_array($featureConfig)) {
                if (isset($featureConfig['enabled'])) {
                    $this->addFeatureWithFlag($box, $key, $featureConfig['enabled']);
                    continue;
                }

                if (empty($featureConfig['class']) && empty($featureConfig['type'])) {
                    // name = $key
                    // relations = $featureConfig
                    $feature = new Feature($key);
                    
                    foreach ($featureConfig as $conditionRelation) {
                        if (empty($conditions[$conditionRelation])) {
                            throw new \Exception('Condition ' . $conditionRelation . ' not found');
                        }
                        $feature->addCondition($conditions[$conditionRelation]);
                    }
                    $box->addFeature($feature);
                    continue;
                }
            }
            
            if (is_string($key) && is_string($featureConfig)) {
                $feature = new Feature($key);
                if (empty($conditions[$featureConfig])) {
                    throw new \Exception('Condition ' . $featureConfig . ' not found');
                }
                $feature->addCondition($conditions[$featureConfig]);
                $box->addFeature($feature);
                continue;
            }
        }

        $this->setParentRelation($box, $config['features']);

        return $box;
    }

    /**
     * @param array $config
     * @return array
     * @throws \ReflectionException
     */
    protected function prepareConditions(array $config)
    {
        $conditions = [];

        foreach ($config as $name => $conditionConfig) {
            $class = !empty($conditionConfig['class']) ? $conditionConfig['class'] : null;

            if (!empty($conditionConfig['type'])) {
                if (null !== $class) {
                    // ingore! class before type... log or throw exception
                } else {
                    $class = $this->guessConditionClassName($conditionConfig['type']);
                }
            }

            if (null === $class) {
                throw new \Exception("Class does not exist.");
            }

            // this is just for the dev draft
            $reflection = new \ReflectionClass($class);
            $condition = null;

            if ($reflection->hasMethod('__construct')) {
                $args = [];
                foreach ($reflection->getMethod('__construct')->getParameters() as $parameter) {
                    $pn = strtolower($parameter->getName());
                    if (!empty($conditionConfig[$pn])) {
                        $args[$pn] = $conditionConfig[$pn];
                    }
                }
                $condition = $reflection->newInstanceArgs($args);
            } else {
                $condition = new $class();
            }

            $condition->setName($name);
            $conditions[$name] = $condition;
        }
        
        return $conditions;
    }
    
    protected function guessConditionClassName($type)
    {
        $path = __DIR__ . '/Conditions/';
        $dir = new \DirectoryIterator($path);
        
        foreach ($dir as $name) {
            $pattern = '/(?P<type>[^Abstract].+)Condition\.(.+)/';
            
            if (preg_match($pattern, $name, $match) 
                && strtolower($type) == strtolower($match['type'])) {
                return sprintf("%s\\Conditions\\%sCondition", __NAMESPACE__, $match['type']);
            }
        }
        return null;
    }
    
    protected function addFeatureWithFlag($box, $name, $flag)
    {
        $factory = $this->conditionFactory;
        
        $feature = new Feature($name);
        $feature->addCondition($factory->create('bool', $flag));
        $box->addFeature($feature);
        
        return $this;
    }

    /**
     * @param FeatureBox $box
     * @param array $config
     */
    protected function setParentRelation(FeatureBox $box, array $config)
    {
        foreach ($config as $name => $item) {
            if (!empty($item['parent'])) {
                $feature = $box->getFeature($name);
                $feature->setParent($box->getFeature($item['parent']));
            }
        }
    }
}
