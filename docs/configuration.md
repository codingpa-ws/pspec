---
title: Configuration
---

# Configuration

PSpec allows simple configuration using a `.pspec.php` file in the root
directory of your project.

The test file is read shortly after `vendor/bin/pspec` is executed and
before all tests are run. It is not possible to use hooks
(`before`/`after`) or test definitions (`describe` etc.) in this file.

## Example

```php
<?php

use CodingPaws\PSpec\Config\Config;
use CodingPaws\PSpec\Console\DocTestFormatter;
use CodingPaws\PSpec\Console\DotTestFormatter;

return Config::new()
  # By default `spec` contains all test files
  # but you can add additional directories
  # to the spec discovery, e.g. for integration
  # tests or a separate part of your app
  ->addDirectory('examples')
  # By default, PSpec runs with the dot test
  # formatter that prints `.` for every passed
  # test and `F` for every failed test.
  #
  # You can write your own formatter and
  # register it here or use one of the two
  # built-in formatters
  ->setFormatter(getenv('CI') ? new DocTestFormatter : new DotTestFormatter)
  # PSpec can generate a test summary in the
  # JUnit format that can be passed to e.g.
  # GitLab during CI to show failures in the UI.
  #
  # Read more: https://docs.gitlab.com/ee/ci/yaml/artifacts_reports.html#artifactsreportsjunit
  ->logJUnit('junit.xml');
```
