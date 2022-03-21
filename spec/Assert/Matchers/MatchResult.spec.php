<?php

use CodingPaws\PSpec\Assert\Matchers\MatchResult;

describe(MatchResult::class, function () {
  let('message', 'An error occurred!');
  subject(fn () => new MatchResult(get('message'), get('pass')));

  describe('#isPass', function () {
    context('when the match did not pass', function () {
      let('pass', false);

      it('returns false', function () {
        expect($this->subject->isPass())->toBe(false);
      });

      context('when using expect->not', function () {
        it('returns true', function () {
          expect($this->subject->isPass(not: true))->toBe(true);
        });
      });
    });

    context('when the match passed', function () {
      let('pass', true);

      it('returns true', function () {
        expect($this->subject->isPass())->toBe(true);
      });

      context('when using expect->not', function () {
        it('returns false', function () {
          expect($this->subject->isPass(not: true))->toBe(false);
        });
      });
    });
  });
});
