<?php

namespace CodingPaws\PSpec;

use CodingPaws\PSpec\Tree\Node;
use DateTime;

require 'vendor/autoload.php';

class Entrypoint
{
  private DateTime $start;

  public function __construct(private string $filename)
  {
    $this->start = date_create();
  }

  public function execute(): void
  {
    $files = $this->parse();
    $this->requireAll($files);
    $this->test();
    $this->finalize();
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
      if (in_array($file, ['.', '..'])) {
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

  private function test(): void
  {
    Node::$root->run();
  }

  private function finalize(): void
  {
    $stats = Node::$root->stats();

    $passes = $stats->countPasses();
    $all = $stats->countAll();
    $failures = $all - $passes;

    $stats->printFailures();

    echo "\e[32m$passes\e[0m passed; \e[31m$failures\e[0m failed.\n";
  }
}
