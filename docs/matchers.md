---
title: Matchers
---

# Matchers

A test is marked as failed if an exception is throwing during its (or its
`before` and `after` hooks) execution. To simplify assertions, PSpec
comes with the `expect` helper and associated matchers.

## Expectations

You use expectations in the structure `expect(ACTUAL)->MATCHER(ARGS);`.

```php
it('adds two numbers together', function () {
  expect(2 + 7)->toBe(9);
});
```

## Negating expectations

An expectation can be negated by using the `not` property on the
expectation before the matcher method name.

```php
// ✅ Pass
expect('dog')->not->toBe('cat');

// ❌ Fail
expect('cat')->not->toBe('cat');
```

## `toBe`

The `toBe` matcher uses strict equality (`===`) to compare the actual
value to an expected value.

```php
// ✅ Pass
expect('henlo')->toBe('henlo');

// ❌ Fail
expect('henlo')->toBe(5);
```

## `toBeCallable`

The `toBeCallable` matcher asserts that a value is callable using PHP’s
native [`is_callable`][is_callable] method.

[is_callable]: https://www.php.net/manual/en/function.is-callable.php

```php
// ✅ Pass
expect(fn () => 1)->toBeCallable();

// ❌ Fail
expect('woof')->toBeCallable();
```

## `toThrow`

The `toThrow` matcher asserts that a value is callable (using
`toBeCallable`) and throws an exception. Optionally, the first argument
can be the class of the exception that is expected to be thrown.

```php
// ✅ Pass
expect(fn () => 1 / 0)->toThrow();

// ✅ Pass
expect(function () {
  throw new Exception('something bad happened!');
})->toThrow(Exception::class);

// ❌ Fail
expect(fn () => 'woof')->toThrow();
```

## `toExtend`

The `toExtend` matcher asserts that a value extends another class using
the [`class_parents`][class_parents] function.

[class_parents]: https://www.php.net/manual/en/function.class-parents

```php
// ✅ Pass
expect(\Exception::class)->toExtend(\Throwable::class);

// ❌ Fail
expect(\Exception::class)->toExtend(\RuntimeException::class);
```

## `toPrint`

The `toPrint` matcher asserts that a value is callable (using
`toBeCallable`) and prints some text to the output, e.g. using `echo`.

Internally, it uses `ob_start` and `ob_get_clean`, which can cause issues
if you use the methods inside the callable too.

```php
// ✅ Pass
expect(function () {
  echo 'hello world!';
})->toPrint('hello');

// ✅ Pass
expect(function () {
  echo 'hello world!';
})->toPrint('hello world!', exact: true);

// ❌ Fail
expect(function () {
  echo 'hello world!';
})->toPrint('doggo ipsum');

// ❌ Fail
expect(function () {
  echo 'doggo ipsum';
})->toPrint('doggo', exact: true);
```
