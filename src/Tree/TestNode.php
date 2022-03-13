<?php

namespace CodingPaws\PSpec\Tree;

use BadFunctionCallException;
use Closure;

class TestNode extends Node
{
  public function __construct(Node $parent, private string $title, private Closure $test)
  {
    parent::__construct($parent);
  }

  public function addDescribe(string $title): DescribeNode
  {
    throw new BadFunctionCallException("Can’t describe() inside an it().");
  }

  public function run(string $indent = ""): void
  {
    $method = $this->test;
    try {
      $method();
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
