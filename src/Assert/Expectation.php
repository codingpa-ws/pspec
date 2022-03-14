<?php

namespace CodingPaws\PSpec\Assert;

use AssertionError;
use Exception;
use Throwable;

class Expectation
{
  public function __construct(private mixed $actual)
  {
  }

  public function toBe(mixed $expectation): void
  {
    $this->assertEquals($expectation, $this->actual, 'expect()->toBe()');
  }

  public function toThrow(?string $class): void
  {
    $this->toBeCallable();

    $actual_class = null;
    try {
      ($this->actual)();
    } catch (Throwable $actual) {
      $actual_class = $actual::class;
    }
    $this->assertEquals($class, $actual_class, 'expect()->toThrow()');
  }

  public function toBeCallable(): void
  {
    $this->assertEquals(is_callable($this->actual), true, 'expect()->toBeCallable()');
  }

  private function assertEquals(mixed $expected, mixed $actual, string $message_prepend = ''): void
  {
    $text = [$message_prepend, "\n    expected: " . $this->serializeExpression($expected) . "\n         got: " . $this->serializeExpression($actual)];
    $this->assert($expected === $actual, join("\n", $text));
  }

  private function assert(bool $ok, string $message = '')
  {
    if (!$ok) {
      throw new AssertionError($message);
    }
  }

  private function serializeExpression(mixed $value): string
  {
    ob_start();
    debug_zval_dump($value);
    $parts = preg_replace('/refcount\(\d+\)/', '', ob_get_contents());
    ob_end_clean();

    return trim($parts);
  }
}
