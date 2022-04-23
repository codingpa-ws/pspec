<?php

namespace CodingPaws\Spec;

use Closure;
use Exception;

class MockCallable
{
  public function __construct(private bool $throw = false)
  {
  }

  private int $calls = 0;

  public function __invoke()
  {
    $this->calls++;

    if ($this->throw) {
      throw new Exception;
    }
  }

  public function calls(): int
  {
    return $this->calls;
  }

  public function asClosure(): Closure
  {
    $callable = $this;

    // TestNode changes $this to Scope
    return fn () => $callable();
  }
}
