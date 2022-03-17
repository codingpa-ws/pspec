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

  let('base_value', 10);

  before(function () {
    subject()->value = $this->base_value;
  });

  describe('#increment', function () {
    it('increments by 1', function () {
      expect(subject()->value)->toBe($this->base_value);

      subject()->increment();

      expect(subject()->value)->not->toBe($this->base_value);
      expect(subject()->value)->toBe($this->base_value + 1);
    });
  });

  describe('#decrement', function () {
    it('decrements by 1', function () {
      expect(subject()->value)->toBe($this->base_value);

      subject()->decrement();
      subject()->decrement();

      expect(subject()->value)->toBe($this->base_value - 2);
    });
  });
});
