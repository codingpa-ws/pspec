<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToContain extends Matcher
{
  public function match(mixed $received, mixed ...$args): MatchResult
  {
    $this->assert(count($args) === 1, 'toContain(...) expects exactly 1 argument.');
    [$expected] = $args;
    $this->assert(is_string($received) || is_array($received), 'toContain(): actual value must be string or array');

    $pass = is_string($received) ? str_contains($received, $expected) : in_array($expected, $received);

    $expected_str = $this->dumps($expected);

    return new MatchResult($this->generateFor([
      'expected' => $this->dumps($received) . ($this->isNot() ? ' doesn’t contain ' : ' contains ') . $expected_str,
      'got' => $pass,
    ], 'String/array doesn’t contain expected value'), $pass);
  }

  public function name(): string
  {
    return 'toContain';
  }
}
