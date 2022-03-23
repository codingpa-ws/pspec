<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToExtend extends Matcher
{
  public function match(mixed $received, mixed ...$args): MatchResult
  {
    $this->assert(count($args) === 1, 'toContain(...) expects exactly 1 argument.');
    [$class] = $args;

    $parents = class_parents($received);
    $pass = in_array($class, $parents);

    $received_class = get_class($received);

    return new MatchResult($this->generateFor([
      'expected' => "$received_class extends $class",
      'it extends' => join(', ', $parents) ?: "<nothing>",
    ]), $pass);
  }

  public function name(): string
  {
    return 'toExtend';
  }
}
