<?php

use CodingPaws\PSpec\Tree\TestResult;

describe(TestResult::class, function () {
  let('name', 'full name');
  let('state', TestResult::STATE_SUCCESS);
  let('throwable', null);
  subject(fn () => new TestResult(get('name'), get('state'), get('throwable')));

  describe('#getName', function () {
    it('returns the name', function () {
      expect(subject()->getName())->toBe($this->name);
    });
  });

  describe('#getState', function () {
    it('returns the state name', function () {
      expect(subject()->getState())->toBe($this->state);
    });
  });

  describe('#getThrowable', function () {
    context('without throwable', function () {
      it('returns null', function () {
        expect(subject()->getThrowable())->toBe(null);
      });
    });

    context('with a throwable passed to the constructor', function () {
      let('throwable', new Exception);

      it('returns the throwable', function () {
        expect(subject()->getThrowable())->toBe($this->throwable);
      });
    });
  });
});
