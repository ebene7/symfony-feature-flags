<?php

/*
 * e7_feature_flags:
 *      default: true
 *      features:
 *          foo: true
 *          bar: true
 *          bazz: [onlysub]
 *          bamm: onlysub
 *      conditions:
 *          onlysub:
 *              type[/class]: host
 *              hostnames: blog.example.com
 */

namespace E7\FeatureFlagsBundle\Feature;

use E7\FeatureFlagsBundle\Context\Context;

/**
 * Class FeatureBoxBuilder
 * @package E7\FeatureFlagsBundle\Feature
 */
class FeatureBoxBuilder {
    
    public function buildFromConfig(array $config)
    {
        $box = new FeatureBox([], new Context());
        $box->setDefaultState(empty($config['default']) ? true : (bool) $config['default']);
        
        $features = [];
        
        $conditions = !empty($config['conditions']) 
            ? $this->prepareConditions($config['conditions']) 
            : [];
        
        foreach ($config['features'] as $key => $featureConfig) {
            if (is_numeric($key) && is_string($featureConfig)) {
                $condition = new Conditions\BooleanCondition($box->getDefaultState());
                $feature = new Feature($featureConfig);
                $feature->addCondition($condition);
                $box->addFeature($feature);
                continue;
            }
            
            if (is_string($key) && is_bool($featureConfig)) {
                $condition = new Conditions\BooleanCondition($featureConfig);
                $feature = new Feature($key);
                $feature->addCondition($condition);
                $box->addFeature($feature);
                continue;
            }
            
            if (is_string($key) && is_array($featureConfig)) {
                if (empty($featureConfig['class']) && empty($featureConfig['type'])) {
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
        }

        return $box;
    }
    
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