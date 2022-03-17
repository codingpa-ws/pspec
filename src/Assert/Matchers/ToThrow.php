<?php

namespace CodingPaws\PSpec\Assert\Matchers;

use Throwable;

class ToThrow extends Matcher
{
  public function match(mixed $received, mixed ...$args): MatchResult
  {
    $this->assert(count($args) <= 1, 'expect()->toThrow() accept one or no argument');

    if ($result = $this->checkCallable($received)) {
      return $result;
    }

    $actual_class = null;
    try {
      ($received)();
    } catch (Throwable $actual) {
      $actual_class = $actual::class;
    }

    $pass = (count($args) === 0 && !is_null($actual_class)) || (count($args) && $actual_class === $args[0]);

    return new MatchResult(
      $this->generateFor([
        'expected' => (count($args) ? $args[0] : 'any exception') . ' ' . ($this->isNot() ? 'not ' : '') . 'to be thrown',
        'was thrown' => $actual_class,
      ], 'expect()->toThrow()'),
      $pass
    );
  }

  private function checkCallable(mixed $received): ?MatchResult
  {
    $result = (new ToBeCallable)->match($received);

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
