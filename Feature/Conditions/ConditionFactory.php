<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

use E7\FeatureFlagsBundle\Feature\Conditions\ConditionInterface;
use E7\FeatureFlagsBundle\Feature\Conditions\ResolverInterface;
use Exception;
use ReflectionClass;

/**
 * Description of ConditionFactory
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class ConditionFactory
{
    /** @var ResolverInterface */
    private $typeResolver;

    /**
     * Constructor
     *
     * @param ResolverInterface $typeResolver
     */
    public function __construct(ResolverInterface $typeResolver)
    {
        $this->typeResolver = $typeResolver;
    }

    /**
     * @param string $method
     * @param array $args
     * @return ConditionInterface|null
     * @throws Exception
     */
    public function __call(string $method, array $args)
    {
        $pattern = '/^create(?P<type>.*)$/';

        if (!preg_match($pattern, $method, $match)) {
            throw new Exception('Method does not exist');
        }

        array_unshift($args, $match['type']);
        return call_user_func_array([$this, 'create'], $args);
    }

    /**
     * @param string $type
     * @param mixed $config
     * @return ConditionInterface
     * @throws Exception
     */
    public function create($type, ...$config)
    {
        $class = $this->typeResolver->resolve($type);

        if (!class_exists($class)) {
            throw new Exception('Condition does not exist or does not implement ConditionInterface');
        }

        $reflection = new ReflectionClass($class);

        if (empty($config) || !$reflection->hasMethod('__construct')) {
            $condition = new $class();
        } else {
            $condition = $reflection->newInstanceArgs($config);
        }

        return $condition;
    }

    /**
     * Factory method
     * 
     * @param string $type
     * @param array $config
     * @return ConditionInterface
     * @throws Exception
     */
    public function createFromConfig($type, array $config = [])
    {
        $class = $this->typeResolver->resolve($type);

        if (!class_exists($class)) {
            throw new Exception('Condition does not exist or does not implement ConditionInterface');
        }

        $reflection = new ReflectionClass($class);

        if ($reflection->hasMethod('__construct')) {
            $args = [];

            /** @var $parameter ReflectionParameter */
            foreach ($reflection->getMethod('__construct')->getParameters() as $parameter) {
                $parameterName = strtolower($parameter->getName());

                if (!isset($config[$parameterName]) && !$parameter->isOptional()) {
                    throw new Exception('Missing mandatory parameter ' . $parameterName);
                }

                if (isset($config[$parameterName])) {
                    $args[$parameterName] = $config[$parameterName];
                }
            }
            $condition = $reflection->newInstanceArgs($args);
        } else {
            $condition = new $class();
        }

        return $condition;
    }
}
