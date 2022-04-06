<?php

use CodingPaws\PSpec\Assert\Matchers\ToThrow;

describe(ToThrow::class, function () {
  let('is_pass', fn () => subject()->execute(...get('args'))->isPass());

  describe('#name', function () {
    it('returns toThrow', function () {
      expect($this->subject->name())->toBe('toThrow');
    });
  });

  describe('#match', function () {
    context('with a callable that throws', function () {
      let('callable', fn () => function () {
        throw new Exception;
      });

      it('returns true', function () {
        expect($this->subject->execute($this->callable)->isPass())->toBe(true);
      });

      context('with the correct exception as argument', function () {
        let('args', [fn () => throw new Exception]);

        it('returns true', function () {
          expect($this->is_pass)->toBe(true);
        });
      });

      context('with null as argument', function () {
        let('args', [fn () => throw new Exception, null]);

        it('returns false', function () {
          expect($this->is_pass)->toBe(false);
        });
      });
    });

    context('with a callable that does not throw', function () {
      let('args', [fn () => 1]);

      it('returns false', function () {
        expect($this->is_pass)->toBe(false);
      });

      context('with an exception as argument', function () {
        let('args', [fn () => 1, Exception::class]);
        it('returns false', function () {
          expect($this->is_pass)->toBe(false);
        });
      });

      context('with null as argument', function () {
        let('args', [fn () => 1, null]);
        it('returns true', function () {
          expect($this->is_pass)->toBe(true);
        });
      });
    });

    context('with a non-callable value', function () {
      it('returns false', function () {
        expect($this->subject->execute([])->isPass())->toBe(false);
      });
    });
  });
});
