<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToBeCallable extends Matcher
{
  public function name(): string
  {
    return "toBeCallable";
  }

  public function match(mixed $received, mixed ...$args): MatchResult
  {
    $this->assert(count($args) === 0, 'expect()->toBeCallable() doesn’t accept parameters.');

    $ok = is_callable($received);

    return new MatchResult($this->generateFor([
      'expected' => $this->isNot() ? 'not a callable' : 'a callable',
      'got' => $this->dumps($received),
    ], 'expect()->toBeCallable()'), $ok);
  }
}
