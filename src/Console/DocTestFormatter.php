<?php

namespace CodingPaws\PSpec\Console;

use CodingPaws\PSpec\Tree\TestResult;

class DocTestFormatter extends TestFormatter
{
  public function printTest(TestResult $result): void
  {
    $indent = $result->getNode()->getIndent();
    $name = $result->getNode()->name();

    echo $indent;

    $this->printPrefix($result);

    echo $name . PHP_EOL;
  }

  private function printPrefix(TestResult $result): void
  {
    if ($result->isGroup()) {
      return;
    }

    $prefix = $result->isSuccessful() ? "\e[32m✔ \e[0m" : "\e[31m✘ \e[0m";

    echo $prefix;
  }
}
