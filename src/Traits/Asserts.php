<?php

namespace CodingPaws\PSpec\Traits;

use AssertionError;

trait Asserts
{
  protected function assert(bool $ok, string $message = '')
  {
    if (!$ok) {
      throw new AssertionError($message);
    }
  }
}
