<?php

namespace CodingPaws\PSpec\Console;

use CodingPaws\PSpec\Tree\TestResult;

class DotTestFormatter extends TestFormatter
{
  public function printTest(TestResult $result): void
  {
    echo match ($result->getState()) {
      TestResult::STATE_FAILURE => "\e[31mF",
      TestResult::STATE_SUCCESS => "\e[32m.",
      default => '',
    };
  }
}
