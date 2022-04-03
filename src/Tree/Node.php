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
  protected string $indent = '';

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
    return $this->variables[$name] = new Variable($name, $value);
  }

  private function addChildNode(Node $node): void
  {
    $this->children[] = $node;
  }

  public function run(PSpec $app): void
  {
    foreach ($this->children as $child) {
      $app->setCurrentScope($child);
      if ($this->parent) {
        $child->setIndent("$this->indent  ");
      }

      $child->run($app);
    }
  }

  public function setIndent(string $indent): void
  {
    $this->indent = $indent;
  }

  public function getIndent(): string
  {
    return $this->indent;
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

  public function resolveVariable(string $name): ?Variable
  {
    $node = $this;

    do {
      if (array_key_exists($name, $node->variables)) {
        return $node->variables[$name];
      }
    } while ($node = $node->parent);

    return null;
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
