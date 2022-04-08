<?php

namespace CodingPaws\PSpec\Assert\Matchers;

class ToPrint extends Matcher
{
  protected function match(mixed $received, string $what, bool $exact = false): MatchResult
  {
    if ($result = $this->checkCallable($received)) {
      return $result;
    }

    ob_start();
    ($received)();
    $content = ob_get_clean();

    $pass = $exact ? $content === $what : str_contains($content, $what);

    return new MatchResult(
      $this->generateFor([
        'expected printed text' => $this->dumps($content),
        $exact ? 'to be exactly' : 'to contain' => $this->dumps($what),
      ], 'expect()->toPrint()'),
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
    return "toPrint";
  }
}
