<?php

namespace CodingPaws\PSpec\Assert\Matchers;

use ArgumentCountError;
use CodingPaws\PSpec\Traits\Asserts;
use ReflectionClass;
use ReflectionFunction;
use Throwable;

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

    if (is_int($value) || is_float($value)) {
      return (string) $value;
    }

    if (is_string($value)) {
      return "'$value'";
    }

    if (is_object($value) || $this->isAssocArray($value)) {
      $parts = [];

      foreach ($value as $k => $v) {
        $parts[] = $this->dumps($k) . ': ' . $this->dumps($v);
      }

      if ($value instanceof Throwable) {
        $parts[] = $this->dumps('message') . ': ' . $this->dumps($value->getMessage());
      }

      return (is_array($value) ? 'array' : $value::class) . '{' . join(', ', $parts) . '}';
    }

    if (is_array($value)) {
      $contents = join(', ', array_map(fn ($v) => $this->dumps($v), $value));
      return "[$contents]";
    }

    ob_start();
    var_dump($value);
    return join(' ', explode("\n", ob_get_clean()));
  }

  private function isAssocArray(mixed $array): bool
  {
    if (!is_array($array) || $array === []) {
      return false;
    }

    return array_keys($array) !== range(0, count($array) - 1);
  }
}
