<?php

namespace CodingPaws\PSpec\Assert;

use CodingPaws\PSpec\Assert\Matchers\Matcher;
use CodingPaws\PSpec\Coverage\Adapter;
use CodingPaws\PSpec\Traits\Asserts;
use RuntimeException;
use Throwable;

/**
 * @property self $not
 * @method void toBe(mixed $what)
 * @method void toBeCallable(mixed $what)
 * @method void toThrow(\Throwable $what = null)
 * @method void toContain(mixed $what)
 * @method void toExtend(string $class)
 */
class Expectation
{
  use Asserts;

  private static array $matchers = [];

  public static function extend(string $matcher): void
  {
    $m = new $matcher;

    self::$matchers[$m->name()] = $matcher;
  }

  public function __construct(
    private mixed $actual,
    private bool $isNot = false,
  ) {
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
    $matcher = $this->getMatcherOrThrow($name);
    $result = $matcher->match($this->actual, ...$arguments);

    $this->assert($result->isPass($this->isNot), $result->getMessage());
  }

  private function getMatcherOrThrow(string $name): Matcher
  {
    if (!array_key_exists($name, self::$matchers)) {
      throw new RuntimeException("Matcher expect(...)->$name() not found.");
    }

    $matcher = self::$matchers[$name];
    $matcher = new $matcher($this->isNot);

    return $matcher;
  }
}
