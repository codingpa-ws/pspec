<?php

namespace CodingPaws\PSpec\Tree;

use BadFunctionCallException;
use Closure;
use CodingPaws\PSpec\Convenience\Scope;
use CodingPaws\PSpec\Coverage\Adapter;
use CodingPaws\PSpec\PSpec;

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
    $errors = [];

    try {
      $this->runBefores($this->scope);
    } catch (\Throwable $th) {
      $errors[] = $th;
    }

    try {
      if (count($errors) === 0) {
        $this->runTest();
      }
    } catch (\Throwable $th) {
      $errors[] = $th;
    }

    try {
      $this->runAfters($this->scope);
    } catch (\Throwable $th) {
      $errors[] = $th;
    }

    $result = new TestResult($this, $errors);
    $this->stats->addTest($result);
    $tree->print($result);
  }

  private function runTest()
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
