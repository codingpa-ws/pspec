<?php

namespace CodingPaws\PSpec\Tree;

use Closure;
use CodingPaws\PSpec\Convenience\Variable;
use CodingPaws\PSpec\Stats;

abstract class Node
{
  private static $variableCache = [];

  protected Stats $stats;
  public array $children = [];
  private array $variables = [];

  public function __construct(private ?Node $parent = null)
  {
    $this->stats = new Stats();
  }

  public function addDescribe(string $title): DescribeNode
  {
    $node = new DescribeNode($this, $title);
    $this->addChildNode($node);
    return $node;
  }

  public function addTest(string $title, Closure $test): TestNode
  {
    $node = new TestNode($this, $title, $test);
    $this->addChildNode($node);
    return $node;
  }

  public function parent(): ?Node
  {
    return $this->parent;
  }

  public function addVariable(string $name, mixed $value): Variable
  {
    return $this->variables[] = new Variable($name, $value);
  }

  private function addChildNode(Node $node): void
  {
    $this->children[] = $node;
  }

  public function run(Tree $tree, string $indent = ""): void
  {
    foreach ($this->children as $child) {
      $tree->setCurrentScope($child);
      self::$variableCache = [];
      $child->run($tree, $this->parent ? "$indent  " : "");
    }
  }

  private function resolveVariable(string $name, ?Variable &$current = null): ?Variable
  {
    $parents = [];

    $parent = $this;

    while ($parent = $parent->parent) {
      $parents[] = $parent;
    }

    foreach (array_reverse($parents) as $parent) {
      $parent->resolveVariable($name, $current);
    }

    foreach ($this->variables as $variable) {
      if ($variable->getName() === $name) {
        $current = $variable;
      }
    }

    return $current;
  }

  public function resolveVariableValue(string $name): mixed
  {
    $variable = $this->resolveVariable($name);

    if (!$variable) {
      return null;
    }

    if (array_key_exists($name, self::$variableCache)) {
      return self::$variableCache[$name];
    }

    return self::$variableCache[$name] = $variable->computeValue();
  }

  public function stats(): Stats
  {
    $stats = $this->stats;

    foreach ($this->children as $child) {
      $stats = $stats->merge($child->stats());
    }

    return $stats;
  }

  abstract public function name(): string;

  public function absoluteName(): string
  {
    $name = [];

    $node = $this;
    do {
      $name[] = $node->name();
    } while ($node = $node->parent);

    return join(' ', array_reverse(array_filter($name)));
  }
}
