<?php

namespace CodingPaws\Spec;

use CodingPaws\PSpec\PSpec;
use CodingPaws\PSpec\Tree\Node;
use CodingPaws\PSpec\Tree\TestResult;

class MockNode extends Node
{
  private int $runs = 0;

  public function name(): string
  {
    return 'MockNode';
  }

  public function run(PSpec $app, string $indent = ""): void
  {
    $app->print(new TestResult($this));
    $this->runs++;
  }

  public function getRuns(): int
  {
    return $this->runs;
  }
}
