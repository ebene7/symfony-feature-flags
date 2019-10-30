<?php

namespace E7\FeatureFlagsBundle\Feature\Conditions;

/**
 * Class TypeResolver
 * @package E7\FeatureFlagsBundle\Feature\Conditions
 */
class TypeResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(string $type): ?string
    {
        if (false !== strstr($type, '\\')
            && class_exists($type)) {
            return $type;
        }

        foreach (new \DirectoryIterator(__DIR__) as $name) {
            $pattern = '/(?P<type>[^Abstract].+)Condition\.(.+)/';

            if (preg_match($pattern, $name, $match)
                && strtolower($type) == strtolower($match['type'])) {
                return sprintf("%s\\%sCondition", __NAMESPACE__, $match['type']);
            }
        }

        return null;
    }
}