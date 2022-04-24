<?php

namespace CodingPaws\PSpec\Console;

use AssertionError;
use CodingPaws\PSpec\Coverage\Adapter;
use CodingPaws\PSpec\Stats;
use CodingPaws\PSpec\Tree\TestResult;
use DateTimeInterface;
use Throwable;

abstract class TestFormatter
{
  abstract public function printTest(TestResult $result): void;

  public function printResult(Stats $stats, DateTimeInterface $startTime): void
  {
    $this->printFailures($stats->failures());

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

  private function printFailures(array $failures): void
  {
    if (count($failures) === 0) {
      return;
    }

    echo "\e[0m";
    echo "\n\nFailures:\n\n";

    $parts = [];

    $i = 0;

    foreach ($failures as $result) {
      $parts = [...$parts, ...$this->stringifySingleFailure($result, $i)];
    }

    echo join("\n\n", $parts);
  }

  private function stringifySingleFailure(TestResult $result, int &$i): array
  {
    $absolute_name = $result->getNode()->absoluteName();

    $parts = [];

    foreach ($result->getThrowables() as $throwable) {
      $parts[] = sprintf(" %d) %s\n", ++$i, $absolute_name) . $this->stringifyError($throwable);
    }

    return $parts;
  }

  private function stringifyError(Throwable $error): string
  {
    $s = "";
    $offset = $error instanceof AssertionError ? 1 : 0;
    $error_line = $error->getTrace()[$offset];
    if (!array_key_exists('file', $error_line)) {
      return "  Unknown exception: $error";
    }
    $error_file_contents = file_get_contents($error_line['file']);
    $lines = explode(PHP_EOL, $error_file_contents);
    $line = $lines[$error_line['line'] - 1];

    $trace = array_filter($error->getTrace(), fn ($t) => !str_starts_with($t['file'], PSPEC_BASE_DIR));

    if (count($trace) === 0) {
      $trace = $error->getTrace();
    }

    $trace = array_map(fn ($t) => $t['file'] . ':' . $t['line'], $trace);

    if (!str_starts_with($error->getFile(), PSPEC_BASE_DIR)) {
      array_unshift($trace, $error->getFile() . ':' . $error->getLine());
    }

    $s .= sprintf("  \e[31m%s: %s\e[0m\n\n", $error::class, $error->getMessage());
    if (!str_contains($line, "toBe")) {
      $s .= sprintf("  \e[33m%s\e[0m\n\n", trim($line));
    }
    return $s . sprintf("  - %s", join("\n  - ", $trace));
  }
}
