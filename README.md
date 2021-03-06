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
  public int $value = 0;

  function increment() {
    $this->value++;
  }
}

// spec/Counter.spec.php
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

      expect(subject()->value)->toBe($this->base_value + 1);
    });
  });
});
```

## Getting started

1. Install PSpec: `composer require --dev codingpaws/pspec`
1. In your project root, create a `spec` directory
1. For a class in your project, for example `Counter`, create a file `spec/Counter.spec.php`
1. Write some tests, like [the example](#example)
1. Run PSpec: `vendor/bin/pspec`

## Why?

PSpec models your application with natural language. In PHPUnit—the de
facto standard in PHP testing—a test file contains a sequential list of
tests. If two tests are related in PHPUnit, such as one authenticated and
one unauthenticated request, it’s hard to understand.

In PSpec, tests are nested under real-world conditions, such as being
signed-in or when a network error occurs. You use `describe` blocks to
organize tests.

## Further reading

- 🔧 [Configuration](https://codingpaws.gitlab.io/pspec/configuration)
- 🪝 [Hooks](https://codingpaws.gitlab.io/pspec/hooks)
- 🧪 [Matchers and assertions](https://codingpaws.gitlab.io/pspec/matchers)
- 🏗️ [Test file structure](https://codingpaws.gitlab.io/pspec/structure)
