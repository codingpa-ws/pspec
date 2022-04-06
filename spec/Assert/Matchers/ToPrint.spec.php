<?php

use CodingPaws\PSpec\Assert\Matchers\ToPrint;

describe(ToPrint::class, function () {
  let('is_pass', fn () => subject()->execute(...get('args'))->isPass());
  let('args', fn () => [function () {
    echo get('output');
  }, get('expectation')]);

  describe('#name', function () {
    it('returns toPrint', function () {
      expect($this->subject->name())->toBe('toPrint');
    });
  });

  describe('#match', function () {
    context('without arguments', function () {
      let('args', [null]);

      it('throws an exception', function () {
        expect(function () {
          $this->is_pass;
        })->toThrow(AssertionError::class);
      });
    });

    context('with a non-callable argument', function () {
      let('args', [null, 'test']);

      it('return false', function () {
        expect($this->is_pass)->toBe(false);
      });
    });

    context('with a substring', function () {
      let('output', 'hello world!');
      let('expectation', 'hello');

      it('returns true', function () {
        expect($this->is_pass)->toBe(true);
      });
    });

    context('with a non-matching string', function () {
      let('output', 'hello world!');
      let('expectation', 'world hello');

      it('returns true', function () {
        expect($this->is_pass)->toBe(false);
      });
    });
  });
});
