<?php

namespace CodingPaws\PSpec;

use Closure;
use CodingPaws\PSpec\Config\Config;
use CodingPaws\PSpec\JUnit\JUnitGenerator;
use CodingPaws\PSpec\Tree\Node;
use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\PSpec\Tree\Tree;
use DateTimeInterface;

class PSpec
{
  private static ?self $instance = null;
  private Node $currentScope;
  private Tree $tree;
  private ?JUnitGenerator $generator;

  public function __construct(private Config $config)
  {
    $this->tree = new Tree($this);
    $junitfile = $this->config->getJUnitFile();
    $this->generator = $junitfile ? new JUnitGenerator($junitfile) : null;
  }

  public static function instance(): ?PSpec
  {
    return self::$instance;
  }

  public function setAsInstance(): void
  {
    self::$instance = $this;
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
      $this->config->getFormatter()->printResult($result, $start);
      $this->handleJUnitLogging($start);
    } else {
      $this->config->getFormatter()->printTest($result);
      $this->generator?->addResult($result);
    }
  }

  public function getConfig(): Config
  {
    return clone $this->config;
  }

  private function handleJUnitLogging(DateTimeInterface $start): void
  {
    if (!$this->generator) {
      return;
    }

    $this->generator->execute($start);
  }
}
