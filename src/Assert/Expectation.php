<?php

namespace CodingPaws\PSpec\Assert;

use AssertionError;
use RuntimeException;
use Throwable;

/**
 * @property self $not
 * @method void toBe(mixed $what)
 * @method void toBeCallable(mixed $what)
 * @method void toThrow(\Throwable $what = null)
 * @method void toContain(mixed $what = null)
 */
class Expectation
{
  private static array $matchers = [];

  public static function extend(string $matcher): void
  {
    $m = new $matcher;

    self::$matchers[$m->name()] = $matcher;
  }

  public function __construct(private mixed $actual, private bool $isNot = false)
  {
  }

  public function __get($name)
  {
    if ($name === 'not') {
      return new Expectation($this->actual, !$this->isNot);
    }

    throw new RuntimeException('Undefined property: ' . self::class . "::$name");
  }

  public function __call($name, $arguments)
  {
    if (!array_key_exists($name, self::$matchers)) {
      throw new RuntimeException("Matcher expect(...)->$name() not found.");
    }

    $matcher = self::$matchers[$name];
    $matcher = new $matcher($this->isNot);
    $result = $matcher->match($this->actual, ...$arguments);

    $this->assert($result->isPass($this->isNot), $result->getMessage());
  }

  private function assert(bool $ok, string $message = '')
  {
    if (!$ok) {
      throw new AssertionError($message);
    }
  }

  private function dumps(mixed $value): string
  {
    ob_start();
    debug_zval_dump($value);
    $parts = preg_replace('/refcount\(\d+\)/', '', ob_get_contents());
    ob_end_clean();

    return trim($parts);
  }
}
