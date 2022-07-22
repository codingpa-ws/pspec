<?php

namespace CodingPaws\PSpec\Tree;

use BadFunctionCallException;
use Closure;
use CodingPaws\PSpec\Convenience\Scope;
use CodingPaws\PSpec\Coverage\Adapter;
use CodingPaws\PSpec\Pipeline;
use CodingPaws\PSpec\PSpec;
use DateTime;

class TestNode extends Node
{
  private Scope $scope;

  public function __construct(Node $parent, private string $title, private Closure $test)
  {
    parent::__construct($parent);
    $this->scope = new Scope($this);
  }

  public function addDescribe(string $title): DescribeNode
  {
    throw new BadFunctionCallException("Can’t describe() inside an it().");
  }

  public function getScope(): Scope
  {
    return $this->scope;
  }

  public function run(PSpec $tree): void
  {
    $start = new DateTime;

    $pipeline = (new Pipeline)
      ->send($this)
      ->pipeAll($this->getHooks($this->scope));

    $errors = [];

    try {
      $errors = $pipeline->run();
    } catch (\Throwable $th) {
      $errors[] = $th;
    }

    $result = new TestResult($this, $errors, $start->diff(new DateTime)->f * 1000);
    $this->stats->addTest($result);
    $tree->print($result);
  }

  public function __invoke()
  {
    Adapter::get()?->startTest();
    $this->test->bindTo($this->scope)();
    Adapter::get()?->endTest();
  }

  public function name(): string
  {
    return $this->title;
  }
}
