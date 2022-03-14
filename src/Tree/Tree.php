<?php

namespace CodingPaws\PSpec\Tree;

use Closure;
use CodingPaws\PSpec\Stats;
use CodingPaws\PSpec\Tree\Node;
use RuntimeException;

class Tree
{
  private static ?Tree $instance = null;
  private static Node $root;
  private Node $currentScope;

  public function __construct()
  {
    if (self::$instance) {
      throw new RuntimeException('Only one Tree instance can be created');
    }

    self::$instance = $this;
    self::$root = new RootNode;
  }

  public function runAllTests(): Stats
  {
    self::$root->run(tree: $this);
    return self::$root->stats();
  }

  public function setCurrentScope(Node $scope): void
  {
    $this->currentScope = $scope;
  }

  // --- helpers ---

  public static function describe(string $title, callable $callback): void
  {
    self::$root = self::$root->addDescribe($title);
    $callback();
    self::$root = self::$root->parent();
  }

  public static function let(string $title, mixed $value): void
  {
    self::$root->addVariable($title, $value);
  }

  public static function get(string $name): mixed
  {
    return self::$instance->currentScope->resolveVariableValue($name);
  }

  public static function it(string $title, callable $callback): void
  {
    self::$root->addTest($title, Closure::fromCallable($callback));
  }
}
