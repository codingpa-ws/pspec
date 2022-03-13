# PSpec

PSpec is a testing framework for PHP, influenced by [RSpec][rspec] and
[jest][jest].

This project is experimental and still needs a lot of work.

[rspec]: https://rspec.info/
[jest]: https://jestjs.io/

## Example

```php
class Counter {
  private int $value = 0;

  function increment() {
    $this->value++;
  }
}

describe(Counter::class, function () {
  subject(new Counter);

  describe('#increment', function () {
    it('increments by 1', function () {
      expect(subject()->value)->toBe(0);

      subject()->increment();

      expect(subject()->value)->toBe(1);
    });
  });
});
```

## Getting started

1. `composer require --dev codingpaws/pspec`