<?php

use CodingPaws\PSpec\Assert\Expectation;
use CodingPaws\PSpec\Tree\Tree;

function describe(string $title, callable $callback): void
{
  Tree::describe($title, $callback);
}

function let(string $title, mixed $value): void
{
  Tree::let($title, $value);
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
  return Tree::get($name);
}

function it(string $title, callable $callback): void
{
  Tree::it($title, $callback);
}

function expect(mixed $actual): Expectation
{
  return new Expectation($actual);
}
