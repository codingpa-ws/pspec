<?php

namespace CodingPaws\PSpec\JUnit;

use CodingPaws\PSpec\Tree\TestResult;
use DateTime;

class JUnitGenerator
{
  private array $tests = [];

  public function __construct(
    private string $filename,
  ) {
  }

  public function addResult(TestResult $result): void
  {
    if ($result->isGroup()) {
      return;
    }
    $this->tests[] = $result;
  }

  private function countFailures(): int
  {
    $i = 0;

    foreach ($this->tests as $result) {
      if (!$result->isSuccessful()) {
        $i++;
      }
    }

    return $i;
  }

  public function execute(DateTime $start): void
  {
    $root = new XmlNode('testsuites', [
      'name' => 'PSpec',
      'tests' => count($this->tests),
      'time' => number_format($start->diff(new DateTime)->f / 1000 / 1000, 8, thousands_separator: ''),
      'timestamp' => $start->format('c'),
      'failures' => $this->countFailures(),
    ]);

    $suite = new XmlNode('testsuite', $root->getAttributes());

    foreach ($this->tests as $result) {
      $test = new XmlNode('testcase', [
        'name' => $result->getNode()->absoluteName(),
        'time' => number_format($result->getDurationInMilliseconds() / 1000, 8, thousands_separator: ''),
      ]);

      foreach ($result->getThrowables() as $throwable) {
        $test->add(new XmlNode('failure', [
          'message' => $throwable->getMessage(),
          'type' => $throwable::class,
        ], (string) $throwable));
      }

      $suite->add($test);
    }

    $root->add($suite);

    file_put_contents($this->filename, (string) $root);
  }
}
