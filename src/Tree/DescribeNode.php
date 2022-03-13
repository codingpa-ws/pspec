<?php

namespace CodingPaws\PSpec\Tree;

class DescribeNode extends Node
{
  public function __construct(Node $parent, private string $title)
  {
    parent::__construct($parent);
  }

  public function run(string $indent = ""): void
  {
    echo $indent . $this->title . PHP_EOL;
    parent::run($indent);
  }

  public function name(): string
  {
    return $this->title;
  }
}
