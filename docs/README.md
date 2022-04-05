---
title: PSpec
---

# PSpec

PSpec is a modern behavior-driven framework for PHP, influenced by
[RSpec][rspec] and [jest][jest].

[rspec]: https://rspec.info/
[jest]: https://jestjs.io/

## Structure

PSpec allows you to write tests in a declarative way. You use the
`describe` and `it` functions to express behavior. Do it just like you
would when explaining it to a co-worker (or rubber ducky :duck:) in a
meeting.

```plaintext
Describe a car
  It can drive to an address
```

```php
describe(Car::class, function () {
  it('can drive to an address', function () {
    $car = new Car;

    $car->driveTo('123 Main St');

    expect($car->getAddress())->toBe('123 Main St');
  });
});
```

:point_right: Read more about [PSpec’s structure](/structure).

## Getting started

You can install PSpec by using [Composer][composer], the PHP package
manager.

1. Install PSpec: `composer require --dev codingpaws/pspec`
1. In your project root, create a `spec` directory
1. For a class in your project, for example `Counter`, create a file `spec/Counter.spec.php`
1. Write some tests, like [the example](#structure)
1. Run PSpec: `vendor/bin/pspec`

[composer]: https://getcomposer.org/

## Why?

PSpec models your application with natural language. In PHPUnit—the de
facto standard in PHP testing—a test file contains a sequential list of
tests. If two tests are related in PHPUnit, such as one authenticated and
one unauthenticated request, it’s hard to understand.

In PSpec, tests are nested under real-world conditions, such as being
signed-in or when a network error occurs. You use `describe` blocks to
organize tests.
