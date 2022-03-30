<?php

namespace CodingPaws\PSpec\Tree;

use Throwable;

class TestResult
{
  public const STATE_GROUP = "group";
  public const STATE_SUCCESS = "success";
  public const STATE_FAILURE = "failure";

  public function __construct(
    private string $fullName,
    private string $state,
    private ?Throwable $throwable = null,
  ) {
  }

  public function getName(): string
  {
    return $this->fullName;
  }

  public function getThrowable(): ?Throwable
  {
    return $this->throwable;
  }

  public function getState(): string
  {
    return $this->state;
  }
}
