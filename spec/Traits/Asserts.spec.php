<?php

use CodingPaws\PSpec\Traits\Asserts;

describe(Asserts::class, function () {
  subject(fn () => new AssertsImpl);

  describe('#assert', function () {
    context('with true', function () {
      it('does nothing', function () {
        expect(function () {
          subject()->wrapperAssert(true);
        })->toThrow(null);
      });
    });

    context('with false', function () {
      it('throws an AssertionError', function () {
        expect(function () {
          subject()->wrapperAssert(false);
        })->toThrow(AssertionError::class);
      });
    });
  });
});

class AssertsImpl
{
  use Asserts;

  public function wrapperAssert()
  {
    $this->assert(...func_get_args());
  }
}
