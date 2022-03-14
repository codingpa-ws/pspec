<?php

namespace CodingPaws\PSpec;

use AssertionError;
use CodingPaws\PSpec\Tree\Node;
use Exception;
use Throwable;

class Stats
{
  private array $tests = [];

  public function addTest(Node $node, ?Throwable $error = null): void
  {
    $this->tests[] = [$node->absoluteName(), $error, array_map(fn ($trace) => $trace['file'], (new Exception())->getTrace())];
  }

  public function merge(Stats $stats): Stats
  {
    $new = new Stats;
    $new->tests = array_merge($this->tests, $stats->tests);
    return $new;
  }

  public function countPasses(): int
  {
    return count(array_filter($this->tests, fn ($x) => !$x[1]));
  }

  public function countFailures(): int
  {
    return count(array_filter($this->tests, fn ($x) => $x[1]));
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
    foreach (array_filter($this->tests, fn ($x) => $x[1]) as [$name, $error, $trace_files]) {
      $parts[] = sprintf(" %d) %s\n", ++$i, $name).$this->printError($error, $trace_files);
    }

    echo join("\n\n", $parts);
  }

  private function printError(Throwable $error, array $internal_trace_files): string
  {
    $s = "";
    $offset = $error instanceof AssertionError ? 1 : 0;
    $error_line = $error->getTrace()[$offset];
    $error_file_contents = file_get_contents($error_line['file']);
    $lines = explode(PHP_EOL, $error_file_contents);
    $line = $lines[$error_line['line'] - 1];

    $trace = array_filter(array_slice($error->getTrace(), $offset), fn ($trace) => !in_array($trace['file'], $internal_trace_files));

    $s .= sprintf("  \e[31m%s: %s\e[0m\n\n", $error::class, $error->getMessage());
    if (!str_contains($line, "toBe")) {
      $s .= sprintf("  \e[33m%s\e[0m\n\n", trim($line));
    }
    return $s . sprintf("  - %s", join("\n  - ", array_map(fn ($t) => $t['file'] . ':' . $t['line'], $trace)));
  }

  public function countAll(): int
  {
    return count($this->tests);
  }
}
