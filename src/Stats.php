<?php

namespace CodingPaws\PSpec;

use AssertionError;
use CodingPaws\PSpec\Tree\TestResult;
use Throwable;

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
    return count(array_filter($this->tests, fn (TestResult $result) => $result->isSuccessful()));
  }

  public function countFailures(): int
  {
    return count(array_filter($this->tests, fn (TestResult $result) => !$result->isSuccessful()));
  }

  public function printFailures(): void
  {
    if (!$this->countFailures()) {
      return;
    }

    echo "\e[0m";
    echo "\n\nFailures:\n\n";

    $parts = [];

    $i = 0;

    foreach ($this->tests as $result) {
      $this->printResult($result, $parts, $i);
    }

    echo join("\n\n", $parts);
  }

  public function printResult(TestResult $result, array &$parts, int &$i): void
  {
    if ($result->isSuccessful()) {
      return;
    }

    $absolute_name = $result->getNode()->absoluteName();

    foreach ($result->getThrowables() as $throwable) {
      $parts[] = sprintf(" %d) %s\n", ++$i, $absolute_name) . $this->printError($throwable);
    }
  }

  private function printError(Throwable $error): string
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

  public function countAll(): int
  {
    return count($this->tests);
  }
}
