<?php

namespace CodingPaws\PSpec\Assert\Matchers;

use AssertionError;
use Closure;
use ReflectionFunction;
use ReflectionObject;

abstract class Matcher
{
  public function __construct(
    private bool $not = false,
  ) {
  }

  abstract public function match(mixed $received, mixed ...$args): MatchResult;
  abstract public function name(): string;

  protected function isNot(): bool
  {
    return $this->not;
  }

  protected function assert(bool $ok, string $message = ""): void
  {
    if (!$ok) {
      throw new AssertionError($message);
    }
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
