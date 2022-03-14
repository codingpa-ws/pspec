<?php

namespace CodingPaws\PSpec\Tree;

use BadFunctionCallException;
use Closure;
use CodingPaws\PSpec\Convenience\Scope;

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

  public function run(Tree $tree, string $indent = ""): void
  {
    $method = $this->test;
    try {
      $method->bindTo($this->scope)();
    } catch (\Throwable $th) {
      $this->stats->addTest($this, $th);
      echo "$indent" . "\e[31m✘ \e[0m" . $this->title . PHP_EOL;
      return;
    }
    $this->stats->addTest($this);
    echo "$indent" . "\e[32m✔ \e[0m" . $this->title . PHP_EOL;
  }

  public function name(): string
  {
    return $this->title;
  }
}
