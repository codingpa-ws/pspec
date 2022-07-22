<?php

use CodingPaws\PSpec\Assert\Expectation;
use CodingPaws\PSpec\PSpec;

if (!defined('PSPEC_BASE_DIR')) {
  return;
}

function before(callable $callback): void
{
  PSpec::before($callback);
}

function after(callable $callback): void
{
  PSpec::after($callback);
}

function around(callable $callable): void
{
  PSpec::around($callable);
}

function context(string $title, callable $callback): void
{
  describe($title, $callback);
}

function describe(string $title, callable $callback): void
{
  PSpec::describe($title, $callback);
}

function let(string $title, mixed $value): void
{
  PSpec::let($title, $value);
}

function subject(mixed $value = null): mixed
{
  if (!func_num_args()) {
    return get('subject');
  }

  return let("subject", $value);
}

function get(string $name): mixed
{
  return PSpec::get($name);
}

function it(string $title, callable $callback): void
{
  PSpec::it($title, $callback);
}

function expect(mixed $actual): Expectation
{
  return new Expectation($actual);
}
