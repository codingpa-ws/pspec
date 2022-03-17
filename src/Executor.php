<?php

namespace CodingPaws\PSpec;

use CodingPaws\PSpec\Assert\Expectation;
use CodingPaws\PSpec\Assert\Matchers\ToBe;
use CodingPaws\PSpec\Assert\Matchers\ToBeCallable;
use CodingPaws\PSpec\Assert\Matchers\ToContain;
use CodingPaws\PSpec\Assert\Matchers\ToThrow;
use CodingPaws\PSpec\Tree\Tree;
use DateTime;

class Executor
{
  private DateTime $start;
  private Tree $tree;

  public function __construct(private string $filename)
  {
    $this->start = date_create();
    $this->tree = new Tree();
  }

  public function registerMatchers(): void
  {
    Expectation::extend(ToBe::class);
    Expectation::extend(ToBeCallable::class);
    Expectation::extend(ToThrow::class);
    Expectation::extend(ToContain::class);
  }

  public function execute(): void
  {
    $files = $this->parse();
    $this->requireAll($files);
    $stats = $this->test();
    $this->printResults($stats);
  }

  private function parse(): array
  {
    return $this->listdir($this->filename);
  }

  private function listdir(string $path): array
  {
    if (!is_dir($path)) {
      return [$path];
    }

    $files = [];

    foreach (scandir($path) as $file) {
      if (in_array($file, ['.', '..']) || str_contains($file, '/vendor/')) {
        continue;
      }

      $files = array_merge($files, $this->listdir("$path/$file"));
    }

    $files = array_filter($files, fn ($file) => str_ends_with($file, ".spec.php"));

    return $files;
  }

  private function requireAll(array $files): void
  {
    foreach ($files as $file) {
      require_once $file;
    }
  }

  private function test(): Stats
  {
    return $this->tree->runAllTests();
  }

  private function printResults(Stats $stats): void
  {
    $this->tree->print($stats, $this->start);
  }
}
