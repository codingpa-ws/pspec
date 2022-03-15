<?php

use CodingPaws\PSpec\Convenience\Scope;
use CodingPaws\PSpec\Tree\RootNode;
use CodingPaws\PSpec\Tree\TestNode;

describe(Scope::class, function () {
  let('value', 1234);
  let('new_value', 12345);
  let('node', fn () => new TestNode(new RootNode, "title", fn () => 1));
  subject(fn () => new Scope(get('node')));

  describe('#__get', function () {
    context('unknown variable', function () {
      it('returns null', function () {
        expect(subject()->number)->toBe(null);
      });
    });

    context('registered variable', function () {
      before(function () {
        $this->node->addVariable('number', $this->value);
      });

      it('returns the value', function () {
        expect(subject()->number)->toBe($this->value);
      });

      context('when cached', function () {
        it('returns the value', function () {
          expect(subject()->number)->toBe($this->value);
          $this->node->addVariable('number', 1);
          expect(subject()->number)->toBe($this->value);
        });
      });
    });

    context('with variable in the parent', function () {
      before(function () {
        $this->node->parent()->addVariable('number', fn () => $this->value);
      });

      it('returns the value', function () {
        expect(subject()->number)->toBe($this->value);
      });

      context('but overwritten', function () {
        before(function () {
          $this->node->addVariable('number', $this->new_value);
        });

        it('returns the child’s value', function () {
          expect(subject()->number)->toBe($this->new_value);
        });
      });
    });
  });

  describe('#__set', function () {
    context('when cached', function () {
      before(fn () => subject()->number);

      it('returns true', function () {
        expect(subject()->isCached('number'))->toBe(true);
      });
    });

    context('when not cached', function () {
      it('returns false', function () {
        expect(subject()->isCached('number'))->toBe(false);
      });
    });
  });
});
