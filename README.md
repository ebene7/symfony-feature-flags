# E7 Symfony Feature Flags Bundle

## Documentation

## Installation/Setup

``` bash
composer require ebene7/symfony-meta-bundle
```

In `bundles.php`:
``` php
<?php

return [
    E7\FeatureFlagsBundle\E7FeatureFlagsBundle::class => ['all' => true],
];
```

## Usage

``` php
<?php
$box = new FeatureBox();

$feature = new Feature('awesome-feature');

```

