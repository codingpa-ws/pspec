<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToExtend extends Matcher
{
  protected function match(mixed $received, mixed $class): MatchResult
  {
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
