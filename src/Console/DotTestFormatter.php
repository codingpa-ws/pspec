<?php

namespace CodingPaws\PSpec\Console;

use CodingPaws\PSpec\Tree\TestResult;

class DotTestFormatter extends TestFormatter
{
  private string $last_state = '';

  public function printTest(TestResult $result): void
  {
    $state = $result->getState();
    echo match ($state) {
      TestResult::STATE_FAILURE => $this->getFailureDot(),
      TestResult::STATE_SUCCESS => $this->getSuccessDot(),
      default => '',
    };

    if (in_array($state, [TestResult::STATE_FAILURE, TestResult::STATE_SUCCESS])) {
      $this->last_state = $state;
    }
  }

  private function getFailureDot(): string
  {
    $color = $this->last_state === TestResult::STATE_FAILURE ? '' : "\e[31m";
    return $color . 'F';
  }

  private function getSuccessDot(): string
  {
    $color = $this->last_state === TestResult::STATE_SUCCESS ? '' : "\e[32m";
    return $color . '.';
  }
}
