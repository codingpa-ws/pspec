<?php

use CodingPaws\PSpec\Assert\Matchers\ToPrint;

describe(ToPrint::class, function () {
  let('is_pass', fn () => subject()->execute(...get('args'))->isPass());
  let('exact', false);
  let('args', fn () => [function () {
    echo get('output');
  }, get('expectation'), 'exact' => get('exact')]);

  describe('#name', function () {
    it('returns toPrint', function () {
      expect($this->subject->name())->toBe('toPrint');
    });
  });

  describe('#match', function () {
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

    context('with an exact match', function () {
      let('output', 'hello world!');
      let('expectation', 'hello world!');
      let('exact', true);

      it('returns true', function () {
        expect($this->is_pass)->toBe(true);
      });

      context('that does not match', function () {
        let('expectation', 'hello');

        it('returns false', function () {
          expect($this->is_pass)->toBe(false);
        });
      });
    });
  });
});
