<?php

namespace CodingPaws\PSpec\Tree;

class DescribeNode extends Node
{
  public function __construct(Node $parent, private string $title)
  {
    parent::__construct($parent);
  }

  public function run(Tree $tree, string $indent = ""): void
  {
    $tree->print(new TestResult($indent . $this->title, TestResult::STATE_GROUP));
    parent::run($tree, $indent);
  }

  public function name(): string
  {
    return $this->title;
  }
}
