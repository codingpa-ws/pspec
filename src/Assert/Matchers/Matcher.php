<?php

namespace CodingPaws\PSpec\Assert\Matchers;

use ArgumentCountError;
use CodingPaws\PSpec\Traits\Asserts;
use ReflectionClass;
use ReflectionFunction;

abstract class Matcher
{
  use Asserts;

  public function __construct(
    private bool $not = false,
  ) {
  }

  public function execute(mixed $received, mixed ...$args): MatchResult
  {
    $class = new ReflectionClass($this);
    $match = $class->getMethod('match');
    $match->setAccessible(true);

    $this->assert($match !== null && $match->isProtected() && !$match->isStatic(), $class->getName() . ' must have a protected `match` method');

    try {
      return $this->{'match'}($received, ...$args);
    } catch (ArgumentCountError $th) {
      throw new ArgumentCountError(sprintf('%s::%s() expects exactly %d argument(s), %d given', $class->getName(), $match->getName(), $match->getNumberOfParameters() - 1, count($args) - 1));
    }
  }

  abstract public function name(): string;

  protected function isNot(): bool
  {
    return $this->not;
  }

  protected function generateFor(array $parts, string $message = ""): string
  {
    $keys = array_keys($parts);
    $max_length = max(...array_map(fn ($key) => strlen($key), $keys));

    $texts = [];

    foreach ($parts as $key => $value) {
      $spaces = '     ' . join('', array: array_fill(0, $max_length - strlen($key), " "));
      $texts[] = "$spaces$key: $value";
    }

    return "$message\n\n" . join("\n", $texts);
  }

  protected function dumps(mixed $value): string
  {
    if (is_callable($value)) {
      $fun = new ReflectionFunction($value);

      return "$fun->name";
    }

    ob_start();
    debug_zval_dump($value);
    $parts = str_replace(["\n", "\e"], ["\\n", "\\e"], trim(preg_replace('/refcount\(\d+\)/', '', ob_get_contents())));
    ob_end_clean();

    return trim($parts);
  }
}
