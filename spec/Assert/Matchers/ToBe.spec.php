<?php

use CodingPaws\PSpec\Assert\Matchers\ToBe;

describe(ToBe::class, function () {
  subject(fn () => new ToBe());
  let('is_pass', fn () => subject()->match(...get('args'))->isPass());

  describe('#name', function () {
    it('returns toBe', function () {
      expect($this->subject->name())->toBe('toBe');
    });
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

    context('for identical values', function () {
      foreach ([
        'arrays' => [[1, 2, 3], [1, 2, 3]],
        'strings' => ['hello', 'hello'],
        'integers' => [575, 575],
        'floats' => [-0.123456, -0.123456],
      ] as $key => $args) {
        context($key, function () use ($args) {
          let('args', $args);

          it('returns a passing result', function () {
            expect($this->is_pass)->toBe(true);
          });
        });
      }
    });

    context('for objects', function () {
      it('returns a non-passing result', function () {
        $obj1 = (object)[];
        $obj2 = (object)[];

        expect($this->subject->match($obj1, $obj2)->isPass())->toBe(false);
      });
    });

    context('for non-identical values', function () {
      foreach ([
        'arrays' => [[3, 2, 1], [1, 2, 3]],
        'strings' => ['hello!', 'hello'],
        'integers' => [10000, 52789],
        'floats' => [3.141, pi()],
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
