<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToBeCallable extends Matcher
{
  public function name(): string
  {
    return "toBeCallable";
  }

  protected function match(mixed $received): MatchResult
  {
    $ok = is_callable($received);

    return new MatchResult($this->generateFor([
      'expected' => $this->isNot() ? 'not a callable' : 'a callable',
      'got' => $this->dumps($received),
    ], 'expect()->toBeCallable()'), $ok);
  }
}
