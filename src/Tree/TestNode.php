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

  public function run(PSpec $tree, string $indent = ""): void
  {
    $method = $this->test;
    try {
      $this->runBefores($this->scope);
      Adapter::get()?->startTest();
      $method->bindTo($this->scope)();
      Adapter::get()?->endTest();
      $this->runAfters($this->scope);
    } catch (\Throwable $th) {
      $this->stats->addTest($this, $th);
      $tree->print(new TestResult($indent . $this->title, TestResult::STATE_FAILURE, $th));
      return;
    }
    $this->stats->addTest($this);
    $tree->print(new TestResult($indent . $this->title, TestResult::STATE_SUCCESS));
  }

  public function name(): string
  {
    return $this->title;
  }
}
