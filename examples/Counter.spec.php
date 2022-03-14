<?php

// This is the primary example in the README.md.
// If you update this file, update the README too.

// Counter.php
class Counter
{
  public int $value = 0;

  public function increment()
  {
    $this->value++;
  }

  public function decrement()
  {
    $this->value--;
  }
}

// Counter.spec.php
describe(Counter::class, function () {
  subject(fn () => new Counter);

  describe('#increment', function () {
    it('increments by 1', function () {
      expect(subject()->value)->toBe(0);

      subject()->increment();

      expect(subject()->value)->toBe(1);
    });
  });

  describe('#decrement', function () {
    it('decrements by 1', function () {
      expect(subject()->value)->toBe(0);

      subject()->decrement();
      subject()->decrement();

      expect(subject()->value)->toBe(-2);
    });
  });
});
