---
title: Structure
---

# Structure

PSpec tests use `describe` and `it` functions to express behavior. Do it
just like you would when explaining it to a co-worker (or rubber ducky
:duck:) in a meeting.

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

This way of writing tests lets you tell a story how a feature or class
works. It helps you both when writing tests, debugging failures, and
understanding features as tests will act as specification of your
features.

## Nesting `describe`s

`describe` blocks form a group of individual tests.

```php
describe('Car', function () {
  describe('with an empty tank', function () {
    it('cannot start its engine', function () {
    });
  });
});
```

## `context` blocks

Like in RSpec, the `context` function is an alias of `describe`. You can
use it interchangeably with `describe`. It has no functional difference
but `context`s make a linguistic difference to tests.

See for yourself and compare the following spec with the previous
example.

```php
describe('Car', function () {
  context('with an empty tank', function () {
    it('cannot start its engine', function () {
    });
  });
});
```

## File structure

For a file to be recognized by PSpec, it must end with `.spec.php`.

Test files usually have one top-level `describe`, for example of a class,
and nested `describe`/`it` blocks.
