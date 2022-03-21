<?php

namespace CodingPaws\PSpec;

use Closure;
use CodingPaws\PSpec\Console\DotTestFormatter;
use CodingPaws\PSpec\Console\TestFormatter;
use CodingPaws\PSpec\Tree\Node;
use CodingPaws\PSpec\Tree\RootNode;
use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\PSpec\Tree\Tree;
use DateTimeInterface;
use RuntimeException;

class PSpec
{
  private static ?self $instance = null;
  private Node $currentScope;
  private Tree $tree;

  public function __construct()
  {
    if (self::$instance) {
      throw new RuntimeException('Only one Tree instance can be created');
    }

    $this->tree = new Tree($this);
    self::$instance = $this;
    $this->formatter = new DotTestFormatter;
  }

  public static function describe(string $title, callable $callback): void
  {

    self::$instance->tree->setRoot(self::$instance->tree->getRoot()->addDescribe($title));

    $callback();
    self::$instance->tree->setRoot(self::$instance->tree->getRoot()->parent());
  }

  public static function before(callable $callback): void
  {
    self::$instance->tree->getRoot()->addBefore($callback);
  }

  public static function after(callable $callback): void
  {
    self::$instance->tree->getRoot()->addAfter($callback);
  }

  public static function let(string $title, mixed $value): void
  {
    self::$instance->tree->getRoot()->addVariable($title, $value);
  }

  public static function get(string $name): mixed
  {
    return self::$instance->currentScope->{'getScope'}()->{$name};
  }

  public static function it(string $title, callable $callback): void
  {
    self::$instance->tree->getRoot()->addTest($title, Closure::fromCallable($callback));
  }

  public function getStats(): Stats
  {
    return self::$instance->tree->getRoot()->stats();
  }

  public function runAllTests(): Stats
  {
    self::$instance->tree->getRoot()->run(app: $this);
    return self::$instance->tree->getRoot()->stats();
  }

  public function setCurrentScope(Node $scope): void
  {
    $this->currentScope = $scope;
  }

  public function print(TestResult|Stats $result, ?DateTimeInterface $start = null): void
  {
    if ($result instanceof Stats) {
      $this->formatter->printResult($result, $start);
    } else {
      $this->formatter->printTest($result);
    }
  }
}
