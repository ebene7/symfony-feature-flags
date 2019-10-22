<?php

namespace E7\FeatureFlagsBundle\Feature;

use E7\FeatureFlagsBundle\Context\Context;

/**
 * Class FeatureBoxBuilder
 * @package E7\FeatureFlagsBundle\Feature
 */
class FeatureBoxBuilder 
{
    private $config;

    public function __construct($container)
    {
        $this->config = $container->getParameter('e7_feature_flags');
        echo __METHOD__.print_r($this->config);
    }

    public function buildFromConfig()
    {
        $config = $this->config;
//
        $box = new FeatureBox([], new Context());
//        $box->setDefaultState(empty($config['default']) ? true : (bool) $config['default']);

//        $features = [];

        $conditions = !empty($config['conditions']) 
            ? $this->prepareConditions($config['conditions']) 
            : [];

        foreach ($config['features'] as $key => $featureConfig) {
//            if (is_numeric($key) && is_string($featureConfig)) {
//                $condition = new Conditions\BooleanCondition($box->getDefaultState());
//                $feature = new Feature($featureConfig);
//                $feature->addCondition($condition);
//                $box->addFeature($feature);
//                continue;
//            }
//
//            if (is_string($key) && is_bool($featureConfig)) {
//                $condition = new Conditions\BooleanCondition($featureConfig);
//                $feature = new Feature($key);
//                $feature->addCondition($condition);
//                $box->addFeature($feature);
//                continue;
//            }
//
            if (is_string($key) && is_array($featureConfig)) {
//                if (empty($featureConfig['class']) && empty($featureConfig['type'])) {
                    $feature = new Feature($key);
// 
                    foreach ($featureConfig['conditions'] as $conditionRelation) {
                        if (empty($conditions[$conditionRelation])) {
                            throw new \Exception('Condition ' . $conditionRelation . ' not found');
                        }
                        $feature->addCondition($conditions[$conditionRelation]);
                    }
                    $box->addFeature($feature);
                    continue;
//                }
            }
        }

        return $box;
    }

    protected function prepareConditions(array $config)
    {
        $conditions = [];

        foreach ($config as $name => $conditionConfig) {
            if (!empty($conditionConfig['hosts'])) { // hack
                $conditionConfig['hostnames'] = $conditionConfig['hosts'];
            }
            
            
            $class = !empty($conditionConfig['class']) ? $conditionConfig['class'] : null;
            
            if (!empty($conditionConfig['type'])) {
                if (null !== $class) {
                    // ingore! class before type... log or throw exception
                } else {
                    if ('bool' == $conditionConfig['type']) {
                        $conditionConfig['type'] = 'boolean'; // hack
                        $conditionConfig['flag'] = isset($conditionConfig['type'])
                            ? (bool) $conditionConfig['type'] : true;
                    }
                    
                    $class = $this->guessConditionClassName($conditionConfig['type']);
                }
            }
            
            if (null === $class) {
                throw new \Exception("Class $class does not exist.");
            }
            
            // this is just for the dev draft
            $reflection = new \ReflectionClass($class);
            $condition = null;
            
            if ($reflection->hasMethod('__construct')) {
                $args = [];
                foreach ($reflection->getMethod('__construct')->getParameters() as $parameter) {
                    $pn = strtolower($parameter->getName());
//                    echo $pn;
//                    print_r($conditionConfig);
                    if (!empty($conditionConfig[$pn])) {
                        $args[$pn] = $conditionConfig[$pn];
                    }
                }
                $condition = $reflection->newInstanceArgs($args);
            } else {
                $condition = new $class();
            }
            
            $conditions[$name] = $condition;
        }
        
        $conditions['__FLAG_TRUE'] = new Conditions\BooleanCondition(true);
        $conditions['__FLAG_FALSE'] = new Conditions\BooleanCondition(false);

//        echo '###' . count($conditions) . '###';
        print_r($conditions);
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
                
//            echo 'HIER '.sprintf("%s\\Conditions\\%sCondition", __NAMESPACE__, $match['type']);
                return sprintf("%s\\Conditions\\%sCondition", __NAMESPACE__, $match['type']);
            }
        }
        return null;
    }
}
