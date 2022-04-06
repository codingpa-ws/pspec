<?php

namespace CodingPaws\PSpec\Assert\Matchers;

use Throwable;

class ToThrow extends Matcher
{
  protected function match(mixed $received, mixed $throwable = null): MatchResult
  {
    if ($result = $this->checkCallable($received)) {
      return $result;
    }

    $actual_class = null;
    try {
      ($received)();
    } catch (Throwable $actual) {
      $actual_class = $actual::class;
    }

    $pass = (func_num_args() === 1 && !is_null($actual_class)) || (func_num_args() > 1 && $actual_class === $throwable);

    return new MatchResult(
      $this->generateFor([
        'expected' => (func_num_args() > 1 ? $throwable : 'any exception') . ' ' . ($this->isNot() ? 'not ' : '') . 'to be thrown',
        'was thrown' => $actual_class,
      ], 'expect()->toThrow()'),
      $pass
    );
  }

  private function checkCallable(mixed $received): ?MatchResult
  {
    $result = (new ToBeCallable)->execute($received);

    if (!$result->isPass()) {
      return $result;
    }

    return null;
  }

  public function name(): string
  {
    return "toThrow";
  }
}
