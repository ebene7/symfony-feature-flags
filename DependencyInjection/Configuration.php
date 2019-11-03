<?php

namespace E7\FeatureFlagsBundle\DependencyInjection;

use Closure;
use Symfony\Component\Config\Definition\Builder\NodeParentInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package E7\FeatureFlagsBundle\DependencyInjection
 * 
 * config example:
 * 
 * e7_feature_flags:
    features:
        feature1: true
        feature2:
            enable: true
            parent: feature1
        feature3: "condition1"
        feature3b: [ "condition4" ]
        
    conditions:
        condition1:
            type: host
            hosts: '*.example.com'
        condition2:
            type: host
            hosts: [ 'foo.example.com', 'www.example.com' ]
        condition3:
            type: ip
            ips: '127.0.0.1'
        condition3b:
            type: ip
            ips: ['127.0.0.1']
        condition3c:
            type: ip
            ips: ['127.0.0.1', '192.168.1.*' ]
        condition4:
            type: Chain
        condition5_bool:
            type: bool
            flag: true
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('e7_feature_flags');

        $rootNode
            ->children()
                ->append($this->buildDefaultSection())
                ->append($this->buildFeaturesSection())
                ->append($this->buildConditionsSection())
            ->end();

        return $treeBuilder;
    }

    /**
     * @return NodeParentInterface
     */
    protected function buildDefaultSection()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('_defaults');

        $node
            ->children()
                ->booleanNode('state')->defaultTrue()->end()
            ->end();

        return $node;
    }

    /**
     * Build the features config section
     *
     * @return NodeParentInterface
     */
    protected function buildFeaturesSection()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('features');

        $node
            ->arrayPrototype()
                ->beforeNormalization()
                    ->ifArray()->then($this->normalizeFeatureArrayCallback())
                ->end() // end: beforeNormalization
                ->beforeNormalization()
                    ->ifTrue()->then(function($v) { return ['conditions' => [ 'enabled' ]]; })
                ->end() // end: beforeNormalization
                ->beforeNormalization()
                    ->ifEmpty()->then(function($v) { return 'boolean' == gettype($v) ? 'disabled' : 'default'; })
                ->end()
                ->beforeNormalization()
                    ->ifString()->then(function($v) { return ['conditions' => [$v]]; })
                ->end() // end: beforeNormalization
                ->children()
                    ->scalarNode('enabled')->end()
                    ->scalarNode('parent')->defaultValue(null)->end()
                    ->arrayNode('conditions')
                        ->scalarPrototype()->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }

    /**
     * Build the conditions config section
     *
     * @return NodeParentInterface
     */
    protected function buildConditionsSection()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('conditions');
        $node
            ->arrayPrototype()
                ->validate() // removes empty config arrays
                    ->always($this->removeEmptyArraysCallback())
                ->end() // end: validate
                ->children()
                    ->scalarNode('type')->defaultValue('bool')->end()
                    ->booleanNode('flag')->end()
                    ->append($this->buildStringToArrayNode('hosts'))
                    ->append($this->buildStringToArrayNode('ips'))
                    ->append($this->buildStringToArrayNode('members'))
                    ->integerNode('percentage')->end() // type: percent
                ->end()
            ->end(); // end: arrayPrototype

        return $node;
    }

    /**
     * @return NodeParentInterface
     */
    protected function buildStringToArrayNode($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);

        $node
            ->beforeNormalization()
                ->ifString()->then(function($v) { return [$v]; })
            ->end() // end: beforeNormalization
            ->scalarPrototype()->end();

        return $node;
    }

    /**
     * @return Closure
     */
    protected function normalizeFeatureArrayCallback()
    {
        return function(&$v) {
            if (!array_key_exists('enabled', $v)
                && !array_key_exists('parent', $v)
                && !array_key_exists('conditions', $v)) {
                $v = ['conditions' => $v ];
                return $v;
            }

            if (array_key_exists('enabled', $v)) {
                if (true === $v['enabled']) {
                    $condition = [ 'enabled' ];
                } else if (!empty($v['enabled'])) {
                    $condition = [$v['enabled']];
                } else {
                    $condition = [ 'boolean' == gettype($v['enabled']) ? 'disabled' : 'default' ];
                }
                $v['conditions'] = $condition;
                unset($v['enabled']);
                return $v;
            } else {
//                $v = ['conditions' => $v ];
                return $v;
            }
        };
    }

    /**
     * @return Closure
     */
    protected function removeEmptyArraysCallback()
    {
        return function($v) {
            foreach ($v as $key => $value) {
                if(is_array($v[$key]) && empty($v[$key])) {
                    unset($v[$key]);
                }
            }
            return $v;
        };
    }
}
