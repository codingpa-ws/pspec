---
title: 💭 Philosophy
---

# PSpec design philosophy

> Less is more.

PSpec aims to make reading and writing tests as simple and fast as
possible. This is only an ideal, and while we do our best to fulfil it,
it is impossible to completely reach. While that is partly because PSpec
is opinionated, another reason is that it’s inherently challenging to
write behavior-driven tests in PHP due to its language design.

Consider the following example. In PHP, variables have to be registered
with the `use` keyword in inline functions. If PSpec didn’t have the
`let` variables, it would cause the inline functions to become very
verbose and both hard to read and change.

```php
$correct_key = '0011011001';
$doorknob = new Doorknob($correct_key);

describe(Doorknob::class, function () use ($correct_key, $doorknob) {
  describe('with a correct key', function () use ($correct_key, $doorknob) {
    it('is unlocked', function () use ($correct_key, $doorknob) {
      $doorknob->attemptUnlock($correct_key);

      expect($doorknob->isUnlocked())->toBe(true);
    });
  });
});
```

To simplify this and allow for dynamically evaulated variables, PSpec
comes with `let` variables.

```php
describe(Doorknob::class, function () {
  let('correct_key', '0011011001');
  subject(fn () => new Doorknob(get('correct_key')));

  describe('with a correct key', function () {
    it('is unlocked', function () {
      $this->subject->attemptUnlock($this->correct_key);

      expect($this->subject->isUnlocked())->toBe(true);
    });
  });
});
```
