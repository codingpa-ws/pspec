<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToBe extends Matcher
{
  protected function match(mixed $received, mixed $expected): MatchResult
  {
    $pass = $received === $expected;

    $expected = $this->dumps($expected);
    $actual = $this->dumps($received);

    return new MatchResult($this->generateFor([
      'expected' => $this->isNot() ? "not $expected" : "$expected",
      'got' => $actual,
    ]), $pass);
  }

  public function name(): string
  {
    return 'toBe';
  }
}
