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
  private array $hooks = [];
  protected string $indent = '';

  public function __construct(private ?Node $parent = null)
  {
    $this->stats = new Stats();
  }

  public function addBefore(Closure $callback): void
  {
    $this->hooks[] = function ($test) use ($callback) {
      Closure::bind($callback, $this)();
      $test();
    };
  }

  public function addHook(callable $callback): void
  {
    $this->hooks[] = $callback;
  }

  public function addAfter(Closure $callback): void
  {
    array_unshift($this->hooks, function (callable $test, callable $report) use ($callback) {
      try {
        $test();
      } catch (\Throwable $th) {
        $report($th);
      }
      Closure::bind($callback, $this)();
    });
  }

  protected function getHooks(Scope $scope): array
  {
    return array_reduce(
      array_map(fn (Node $node) => array_map(fn (Closure $hook) => $hook->bindTo($scope), $node->hooks), $this->reversedParents()),
      fn (array $all, array $hooks) => array_merge($all, $hooks),
      []
    );
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
