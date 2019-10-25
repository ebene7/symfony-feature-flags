# E7 Symfony Feature Flags Bundle

The Bundle provides Feature Flags/Toggles for your Application. You can easily
configure in yaml-File, use services or just PHP.

It also offers you...
* Twig Extension
* Debug Webtoolbar Panel
* Debug CLI Commands

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

