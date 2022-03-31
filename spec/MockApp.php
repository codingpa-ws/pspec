<?php

namespace CodingPaws\Spec;

use CodingPaws\PSpec\PSpec;
use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\PSpec\Stats;
use DateTimeInterface;

class MockApp extends PSpec
{
  private array $prints = [];

  public function __construct()
  {
  }

  public function print(TestResult|Stats $result, ?DateTimeInterface $start = null): void
  {
    $this->prints[] = $result;
  }

  public function getPrints(): array
  {
    return $this->prints;
  }
}
