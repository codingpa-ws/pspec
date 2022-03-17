<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToBe extends Matcher
{
  public function match(mixed $received, mixed ...$args): MatchResult
  {
    $this->assert(count($args) === 1, 'toBe(...) expects exactly 1 argument.');

    $pass = $received === $args[0];

    $expected = $this->dumps($args[0]);
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
