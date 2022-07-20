<?php

namespace CodingPaws\PSpec\Convenience;

class Variable
{
  public function __construct(private string $name, private mixed $value)
  {
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function computeValue(): mixed
  {
    if (!is_string($this->value) && is_callable($this->value)) {
      return ($this->value)();
    }

    return $this->value;
  }
}
