<?php

namespace CodingPaws\PSpec;

use CodingPaws\PSpec\Tree\TestResult;

class Stats
{
  private array $tests = [];

  public function addTest(TestResult $result): void
  {
    $this->tests[] = $result;
  }

  public function merge(Stats $stats): Stats
  {
    $new = new Stats;
    $new->tests = array_merge($this->tests, $stats->tests);
    return $new;
  }

  public function countPasses(): int
  {
    return $this->countAll() - $this->countFailures();
  }

  public function countFailures(): int
  {
    return count($this->failures());
  }

  public function failures(): array
  {
    return array_filter($this->tests, fn (TestResult $result) => !$result->isSuccessful());
  }

  public function countAll(): int
  {
    return count($this->tests);
  }
}
