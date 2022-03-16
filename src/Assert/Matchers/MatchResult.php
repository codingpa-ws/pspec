<?php

namespace CodingPaws\PSpec\Assert\Matchers;

final class MatchResult
{
  public function __construct(private string $message, private bool $pass)
  {
  }

  public function isPass(bool $not = false): bool
  {
    return $this->pass ^ $not;
  }

  public function getMessage(): string
  {
    return $this->message;
  }
}
