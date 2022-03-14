<?php

namespace CodingPaws\PSpec\Console;

use CodingPaws\PSpec\Tree\TestResult;

class DocTestFormatter extends TestFormatter
{
  public function printTest(TestResult $result): void
  {
    preg_match('/^(\s*)(.+)/', $result->getName(), $matches);
    [,$indent, $name] = $matches;

    echo $indent;

    echo match ($result->getState()) {
      TestResult::STATE_FAILURE => "\e[31m✘ \e[0m",
      TestResult::STATE_SUCCESS => "\e[32m✔ \e[0m",
      default => '',
    };

    echo $name . PHP_EOL;
  }
}
