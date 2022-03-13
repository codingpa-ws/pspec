<?php

use CodingPaws\PSpec\Assert\Expectation;
use CodingPaws\PSpec\Tree\Node;
use CodingPaws\PSpec\Tree\RootNode;

Node::$root = new RootNode;

function describe(string $title, callable $callback): void
{
  Node::$root = Node::$root->addDescribe($title);
  $callback();
  Node::$root = Node::$root->parent();
}

function let(string $title, mixed $value): void
{
  Node::$root->addVariable($title, $value);
}

function subject(mixed $value = null): mixed
{
  if (!func_num_args()) {
    return get('subject');
  }

  let("subject", $value);
  return null;
}

function get(string $name): mixed
{
  return Node::$testRoot->resolveVariableValue($name);
}

function it(string $title, callable $callback): void
{
  Node::$root->addTest($title, Closure::fromCallable($callback));
}

function expect(mixed $actual): Expectation
{
  return new Expectation($actual);
}
