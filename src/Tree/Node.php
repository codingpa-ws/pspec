<?php

namespace CodingPaws\PSpec\Tree;

use Closure;
use CodingPaws\PSpec\Convenience\Scope;
use CodingPaws\PSpec\Convenience\Variable;
use CodingPaws\PSpec\PSpec;
use CodingPaws\PSpec\Stats;

abstract class Node
{
  protected Stats $stats;
  public array $children = [];
  private array $variables = [];
  private array $before = [];
  private array $after = [];

  public function __construct(private ?Node $parent = null)
  {
    $this->stats = new Stats();
  }

  public function addBefore(callable $callback): void
  {
    $this->before[] = $callback;
  }

  public function addAfter(callable $callback): void
  {
    $this->after[] = $callback;
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

  public function run(PSpec $app, string $indent = ""): void
  {
    foreach ($this->children as $child) {
      $app->setCurrentScope($child);
      $child->run($app, $this->parent ? "$indent  " : "");
    }
  }

  public function runBefores(Scope $scope): void
  {
    $parents = $this->reversedParents();

    foreach ($parents as $parent) {
      foreach ($parent->before as $callable) {
        Closure::bind($callable, $scope)();
      }
    }
  }

  public function runAfters(Scope $scope): void
  {
    $parents = array_reverse($this->reversedParents());

    foreach ($parents as $parent) {
      foreach ($parent->after as $callable) {
        Closure::bind($callable, $scope)();
      }
    }
  }

  private function resolveVariable(string $name, ?Variable &$current = null): ?Variable
  {
    $parents = $this->reversedParents();

    foreach ($parents as $parent) {
      $parent->resolveVariable($name, $current);
    }

    foreach ($this->variables as $variable) {
      if ($variable->getName() === $name) {
        $current = $variable;
      }
    }

    return $current;
  }

  private function reversedParents(): array
  {
    $parents = [];

    $parent = $this;

    while ($parent = $parent->parent) {
      $parents[] = $parent;
    }

    return array_reverse($parents);
  }

  public function resolveVariableValue(string $name): mixed
  {
    $variable = $this->resolveVariable($name);

    if (!$variable) {
      return null;
    }

    return $variable->computeValue();
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
