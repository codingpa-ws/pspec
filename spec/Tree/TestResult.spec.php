<?php

use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\Spec\MockNode;

describe(TestResult::class, function () {
  let('example_node', new MockNode);
  let('name', 'full name');
  let('throwables', []);
  subject(fn () => new TestResult(get('example_node'), get('throwables')));

  describe('#getNode', function () {
    it('returns the node', function () {
      expect(subject()->getNode())->toBe($this->example_node);
    });
  });

  describe('#getThrowables', function () {
    context('without throwables', function () {
      it('returns an empty array', function () {
        expect(subject()->getThrowables())->toBe([]);
      });
    });

    context('with a throwable passed to the constructor', function () {
      let('throwables', [new Exception]);

      it('returns the throwables', function () {
        expect(subject()->getThrowables())->toBe($this->throwables);
      });
    });
  });

  describe('#isSuccessful', function () {
    context('without throwables', function () {
      it('returns true', function () {
        expect(subject()->isSuccessful())->toBe(true);
      });
    });

    context('with a throwable passed to the constructor', function () {
      let('throwables', [new Exception]);

      it('returns the throwables', function () {
        expect(subject()->isSuccessful())->toBe(false);
      });
    });
  });
});
