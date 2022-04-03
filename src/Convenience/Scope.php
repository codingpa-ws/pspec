<?php

namespace CodingPaws\PSpec\Convenience;

use CodingPaws\PSpec\Tree\TestNode;
use ReflectionClass;

class Scope
{
  private array $cache = [];

  public function __construct(private TestNode $node)
  {
  }

  public function __get($name): mixed
  {
    if ($this->isCached($name)) {
      return $this->cache[$name];
    }

    $variable = $this->node->resolveVariable($name);
    $value = $variable?->computeValue();

    if ($name === 'subject' && is_null($variable)) {
      $value = $this->generateSubjectFromTopDescribe();
    }

    return $this->cache[$name] = $value;
  }

  public function __set($name, $value): void
  {
    $this->cache[$name] = $value;
  }

  public function isCached(string $name): bool
  {
    return array_key_exists($name, $this->cache);
  }

  private function generateSubjectFromTopDescribe(): mixed
  {
    $node = $this->node;
    $root = null;

    while ($node = $node->parent()) {
      if ($node->name()) {
        $root = $node;
      }
    }

    try {
      $class = new ReflectionClass($root->name());
      return $class->newInstance();
    } catch (\Throwable $th) {
      return null;
    }
  }
}
