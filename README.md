# PSpec

PSpec is a testing framework for PHP, influenced by [RSpec][rspec] and
[jest][jest].

This project is experimental and still needs a lot of work.

[rspec]: https://rspec.info/
[jest]: https://jestjs.io/

## Example

```php
// src/Counter.php
class Counter {
  private int $value = 0;

  function increment() {
    $this->value++;
  }
}

// spec/Counter.spec.js
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

1. Install PSpec: `composer require --dev codingpaws/pspec`
1. In your project root, create a `spec` directory
1. For a class in your project, for example `Counter`, create a file `spec/Counter.spec.js`
1. Write some tests, like [the example](#example)
1. Run PSpec: `vendor/bin/pspec`
