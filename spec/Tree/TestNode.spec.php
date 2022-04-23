<?php

use CodingPaws\PSpec\Convenience\Scope;
use CodingPaws\PSpec\Tree\TestNode;
use CodingPaws\Spec\MockApp;
use CodingPaws\Spec\MockCallable;
use CodingPaws\Spec\MockNode;

describe(TestNode::class, function () {
  let('title', 'adds two numbers');
  let('test', fn () => new MockCallable);
  let('app', fn () => new MockApp);
  subject(fn () => new TestNode(new MockNode, get('title'), get('test')->asClosure()));

  describe('#addDescribe', function () {
    it('throws an exception', function () {
      expect(fn () => subject()->addDescribe('test'))
        ->toThrow(BadFunctionCallException::class);
    });
  });

  describe('#getScope', function () {
    it('returns a scope', function () {
      expect(subject()->getScope()::class)->toBe(Scope::class);
    });
  });

  describe('#name', function () {
    it('returns the node name', function () {
      expect(subject()->name())->toBe($this->title);
    });
  });

  describe('#run', function () {
    context('without hooks', function () {
      itRunsTheTest();
    });

    context('with hooks', function () {
      let('before', fn () => new MockCallable);
      let('after', fn () => new MockCallable);

      before(function () {
        subject()->parent()->addBefore($this->before->asClosure());
        subject()->parent()->addAfter($this->after->asClosure());
      });

      itRunsTheTest();

      it('runs the hooks', function () {
        subject()->run($this->app);

        expect($this->before->calls())->toBe(1);
        expect($this->after->calls())->toBe(1);
      });

      context('when the before hook fails', function () {
        let('before', fn () => new MockCallable(throw: true));

        it('does not run the test but the after hook', function () {
          subject()->run($this->app);

          expect($this->before->calls())->toBe(1);
          expect($this->test->calls())->toBe(0);
          expect($this->after->calls())->toBe(1);
        });
      });

      context('when the test fails', function () {
        let('test', fn () => new MockCallable(throw: true));

        it('runs the test and hooks', function () {
          subject()->run($this->app);

          expect($this->before->calls())->toBe(1);
          expect($this->test->calls())->toBe(1);
          expect($this->after->calls())->toBe(1);
        });
      });

      context('when the after hook fails', function () {
        let('after', fn () => new MockCallable(throw: true));

        it('runs the test and hooks', function () {
          subject()->run($this->app);

          expect($this->before->calls())->toBe(1);
          expect($this->test->calls())->toBe(1);
          expect($this->after->calls())->toBe(1);
        });
      });
    });
  });
});

function itRunsTheTest()
{
  it('runs the test', function () {
    subject()->run($this->app);

    expect($this->test->calls())->toBe(1);
  });
}
