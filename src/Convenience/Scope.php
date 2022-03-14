<?php

namespace CodingPaws\PSpec\Convenience;

use CodingPaws\PSpec\Tree\TestNode;

class Scope
{
  private array $cache = [];

  public function __construct(private TestNode $node)
  {
  }

  public function __get($name): mixed
  {
    if (array_key_exists($name, $this->cache)) {
      return $this->cache[$name];
    }

    return $this->cache[$name] = $this->node->resolveVariableValue($name);
  }

  public function __set($name, $value): void
  {
    $this->cache[$name] = $value;
  }
}
