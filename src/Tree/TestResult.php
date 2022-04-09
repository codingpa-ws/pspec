<?php

namespace CodingPaws\PSpec\Tree;

class TestResult
{
  public function __construct(
    private Node $node,
    private array $throwables = [],
    private float $milliseconds = 0,
  ) {
  }

  public function getNode(): Node
  {
    return $this->node;
  }

  public function getThrowables(): array
  {
    return $this->throwables;
  }

  public function isSuccessful(): bool
  {
    return count($this->throwables) === 0;
  }

  public function isGroup(): bool
  {
    return $this->node instanceof DescribeNode;
  }

  public function getDurationInMilliseconds(): float
  {
    return $this->milliseconds;
  }
}
