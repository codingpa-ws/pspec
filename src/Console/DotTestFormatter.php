<?php

namespace CodingPaws\PSpec\Console;

use CodingPaws\PSpec\Tree\TestResult;

class DotTestFormatter extends TestFormatter
{
  private ?bool $last_state = null;

  public function printTest(TestResult $result): void
  {
    if ($result->isGroup()) {
      return;
    }

    $success = $result->isSuccessful();

    echo $success ? $this->getSuccessDot() : $this->getFailureDot();

    $this->last_state = $success;
  }

  private function getFailureDot(): string
  {
    $color = $this->last_state === false ? '' : "\e[31m";
    return $color . 'F';
  }

  private function getSuccessDot(): string
  {
    $color = $this->last_state ? '' : "\e[32m";
    return $color . '.';
  }
}
