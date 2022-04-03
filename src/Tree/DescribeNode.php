<?php

namespace CodingPaws\PSpec\Tree;

use CodingPaws\PSpec\PSpec;

class DescribeNode extends Node
{
  public function __construct(Node $parent, private string $title)
  {
    parent::__construct($parent);
  }

  public function run(PSpec $app, string $indent = ""): void
  {
    $app->print(new TestResult($this));
    parent::run($app, $indent);
  }

  public function name(): string
  {
    return $this->title;
  }
}
