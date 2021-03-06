<?php

namespace CodingPaws\PSpec\Assert;

use CodingPaws\PSpec\Assert\Matchers\Matcher;
use CodingPaws\PSpec\Traits\Asserts;
use RuntimeException;

/**
 * @property self $not
 * @method self toBe(mixed $what)
 * @method self toBeCallable(mixed $what)
 * @method self toThrow(\Throwable $what = null)
 * @method self toContain(mixed $what)
 * @method self toExtend(string $class)
 * @method self toPrint(string $what, bool $exact = false)
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

  public function __call($name, $arguments): self
  {
    $matcher = $this->getMatcherOrThrow($name);
    $result = $matcher->execute($this->actual, ...$arguments);

    $this->assert($result->isPass($this->isNot), $result->getMessage());

    return $this;
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
