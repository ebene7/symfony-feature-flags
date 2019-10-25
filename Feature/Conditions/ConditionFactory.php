<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

/**
 * Description of ConditionFactory
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class ConditionFactory
{
    /**
     * @param string $method
     * @param array $args
     * @return ConditionInterface|null
     * @throws \Exception
     */
    public function __call(string $method, array $args)
    {
        $pattern = '/^create(?P<type>.*)$/';

        if (!preg_match($pattern, $method, $match)) {
            throw new \Exception('Method does not exist');
        }

        array_unshift($args, $match['type']);
        return call_user_func_array([$this, 'create'], $args);
    }

    public function create($type, ...$config)
    {
        $class = $this->guessClassName($type);

        if (!class_exists($class)) {
            throw new \Exception('Condition does not exist or does not implement ConditionInterface');
        }

        $reflection = new \ReflectionClass($class);

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
     * @return \E7\FeatureFlagsBundle\Feature\Conditions\ConditionInterface
     * @throws \Exception
     */
    public function createFromConfig($type, array $config = [])
    {
        $class = $this->guessClassName($type);

        if (!class_exists($class)) {
            throw new \Exception('Condition does not exist or does not implement ConditionInterface');
        }

        $reflection = new \ReflectionClass($class);

        if ($reflection->hasMethod('__construct')) {
            $args = [];

            /** @var $parameter ReflectionParameter */
            foreach ($reflection->getMethod('__construct')->getParameters() as $parameter) {
                $parameterName = strtolower($parameter->getName());

                if (!isset($config[$parameterName]) && !$parameter->isOptional()) {
                    throw new \Exception('Missing mandatory parameter ' . $parameterName);
                }

                $args[$parameterName] = $config[$parameterName];
            }
            $condition = $reflection->newInstanceArgs($args);
        } else {
            $condition = new $class();
        }

        return $condition;
    }

    /**
     * @param string $type
     * @return string|null
     */
    protected function guessClassName(string $type): string
    {
        if (false !== strstr($type, '\\')) {
            return $type;
        }

        foreach (new \DirectoryIterator(__DIR__) as $name) {
            $pattern = '/(?P<type>[^Abstract].+)Condition\.(.+)/';
            
            if (preg_match($pattern, $name, $match) 
                && strtolower($type) == strtolower($match['type'])) {
                return sprintf("%s\\%sCondition", __NAMESPACE__, $match['type']);
            }
        }

        return $type;
    }
}
