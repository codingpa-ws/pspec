<?php

namespace CodingPaws\PSpec;

use Throwable;

class Pipeline
{
  private $callable;
  private array $pipes = [];
  private array $errors = [];

  public function send(callable $callable): self
  {
    $this->callable = $callable;

    return $this;
  }

  public function pipeAll(array $callables): self
  {
    foreach ($callables as $callable) {
      $this->pipe($callable);
    }

    return $this;
  }

  private function pipe(callable $callable): self
  {
    $this->pipes[] = $callable;

    return $this;
  }

  public function run(): array
  {
    $value = array_reduce(array_reverse($this->pipes), function (callable $stack, callable $pipe) {
      return function () use ($stack, $pipe) {
        return $pipe($stack, fn (Throwable $throwable) => $this->report($throwable));
      };
    }, $this->callable);

    $value($this->callable);

    return $this->errors;
  }

  private function report(Throwable $throwable): void
  {
    $this->errors[] = $throwable;
  }
}
