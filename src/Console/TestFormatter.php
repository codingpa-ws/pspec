<?php

namespace CodingPaws\PSpec\Console;

use CodingPaws\PSpec\Coverage\Adapter;
use CodingPaws\PSpec\Stats;
use CodingPaws\PSpec\Tree\TestResult;
use DateTimeInterface;

abstract class TestFormatter
{
  abstract public function printTest(TestResult $result): void;

  public function printResult(Stats $stats, DateTimeInterface $startTime): void
  {
    $stats->printFailures();

    $passes = $stats->countPasses();
    $all = $stats->countAll();
    $failures = $all - $passes;

    $ms = $startTime->diff(date_create())->f * 1000;
    $ms = number_format($ms, 0) . 'ms';

    echo "\n\n\e[32m$passes\e[0m passed; \e[31m$failures\e[0m failed; finished in $ms.\n";

    if ($coverage = Adapter::get()?->computeCoverageByFile()) {
      $this->printCoverage($coverage, Adapter::get()?->computeTotalCoverage());
    }
  }

  private function printCoverage(array $coverage, float $total): void
  {
    $replace_cwd = fn ($f) => str_replace(getcwd(), '.', $f);

    echo "\nCoverage report (" . $this->formatPercent($total) . "):\n";

    foreach ($coverage as $file => $percent) {
      echo '  ' . $replace_cwd($file) . ': ' . $this->formatPercent($percent) . "\n";
    }
  }

  private function formatPercent(float $percent): string
  {
    return number_format(100 * $percent, decimals: 2) . '%';
  }
}
