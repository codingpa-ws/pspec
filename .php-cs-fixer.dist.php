<?php

$finder = PhpCsFixer\Finder::create()
  ->exclude('vendor')
  ->exclude('tools')
  ->exclude('.gitlab')
  ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
  '@PSR12' => true,
  '@PHP80Migration' => true,
  'new_with_braces' => false,
  'no_extra_blank_lines' => true,
  'no_unused_imports' => true,
])
  ->setIndent("  ")
  ->setFinder($finder);
