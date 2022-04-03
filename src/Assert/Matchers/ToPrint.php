<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToPrint extends Matcher
{
  public function match(mixed $received, mixed ...$args): MatchResult
  {
    $this->assert(count($args) === 1, 'expect()->toPrint() accepts exactly one argument');

    if ($result = $this->checkCallable($received)) {
      return $result;
    }

    ob_start();
    ($received)();
    $content = ob_get_clean();

    $pass = str_contains($content, $args[0]);

    return new MatchResult(
      $this->generateFor([
        'expected printed text' => $this->dumps($content),
        'to contain' => $this->dumps($args[0]),
      ], 'expect()->toPrint()'),
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
    return "toPrint";
  }
}
