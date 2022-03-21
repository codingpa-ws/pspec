<?php

use CodingPaws\PSpec\Assert\Matchers\ToContain;

describe(ToContain::class, function () {
  subject(fn () => new ToContain());
  let('is_pass', fn () => subject()->match(...get('args'))->isPass());

  describe('#name', function () {
    it('returns toContain', function () {
      expect($this->subject->name())->toBe('toContain');
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

    context('for contained values', function () {
      foreach ([
        'an array' => [[1, 2, 3], 1],
        'strings' => ['hello', 'ello'],
        'string with full substring' => ['hello', 'hello'],
      ] as $key => $args) {
        context($key, function () use ($args) {
          let('args', $args);

          it('returns a passing result', function () {
            expect($this->is_pass)->toBe(true);
          });
        });
      }
    });

    context('for values that are not contained', function () {
      foreach ([
        'an array' => [[1, 2, 3], 4],
        'strings' => ['hello', 'henlo'],
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
