<?php

$finder = PhpCsFixer\Finder::create()
  ->exclude('vendor')
  ->exclude('tools')
  ->exclude('.gitlab')
  ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
  '@PSR12' => true,
  'new_with_braces' => false,
])
  ->setIndent("  ")
  ->setFinder($finder);
