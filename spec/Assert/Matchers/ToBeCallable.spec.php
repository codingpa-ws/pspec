<?php

use CodingPaws\PSpec\Assert\Matchers\ToBeCallable;

describe(ToBeCallable::class, function () {
  subject(fn () => new ToBeCallable);
  let('is_pass', fn () => subject()->match(...get('args'))->isPass());

  describe('#name', function () {
    it('returns toBeCallable', function () {
      expect($this->subject->name())->toBe('toBeCallable');
    });
  });

  describe('#match', function () {
    context('with more than one argument', function () {
      it('throws an exception', function () {
        expect(function () {
          $this->subject->match(null, 1);
        })->toThrow(AssertionError::class);
      });
    });

    context('with a callable value', function () {
      let('args', [fn () => 1]);
      it('returns true', function () {
        expect($this->is_pass)->toBe(true);
      });
    });

    context('with a non-callable value', function () {
      let('args', [[]]);

      it('returns false', function () {
        expect($this->is_pass)->toBe(false);
      });
    });
  });
});
