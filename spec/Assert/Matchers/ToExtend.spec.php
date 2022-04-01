<?php

use CodingPaws\PSpec\Assert\Matchers\Matcher;
use CodingPaws\PSpec\Assert\Matchers\ToExtend;

class A
{
}
class B extends A
{
}
class C extends B
{
}
class D
{
}

describe(ToExtend::class, function () {
  let('is_pass', fn () => subject()->match(...get('args'))->isPass());

  describe('#name', function () {
    it('returns toExtend', function () {
      expect($this->subject->name())->toBe('toExtend');
    });
  });

  it('is a matcher', function () {
    expect($this->subject)->toExtend(Matcher::class);
  });

  describe('#match', function () {
    context('with no arguments', function () {
      it('throws an exception', function () {
        expect(function () {
          $this->subject->match(null);
        })->toThrow(AssertionError::class);
      });
    });

    context('with more than one arguments', function () {
      it('throws an exception', function () {
        expect(function () {
          $this->subject->match(null, 1, 2);
        })->toThrow(AssertionError::class);
      });
    });

    context('with a class that extends', function () {
      foreach ([
        'the direct parent' => [new B, A::class],
        'an indirect parent' => [new C, A::class],
        'the direct parent with a parent' => [new C, B::class],
      ] as $key => $args) {
        context($key, function () use ($args) {
          let('args', $args);

          it('returns a passing result', function () {
            expect($this->is_pass)->toBe(true);
          });
        });
      }
    });

    context('with a class that doesn’t extend', function () {
      foreach ([
        'unrelated' => [new B, D::class],
        'extended class' => [new A, B::class],
      ] as $key => $args) {
        context($key, function () use ($args) {
          let('args', $args);

          it('returns a failing result', function () {
            expect($this->is_pass)->toBe(false);
          });
        });
      }
    });
  });
});
