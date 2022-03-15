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
      it('returns the value', function () {
        $this->node->addVariable('number', $this->value);
        expect(subject()->number)->toBe($this->value);
      });

      context('when cached', function () {
        it('returns the value', function () {
          $this->node->addVariable('number', fn () => $this->value);
          expect(subject()->number)->toBe($this->value);
          $this->node->addVariable('number', 1);
          expect(subject()->number)->toBe($this->value);
        });
      });
    });

    context('with variable in the parent', function () {
      it('returns the value', function () {
        $this->node->parent()->addVariable('number', fn () =>  $this->value);
        expect(subject()->number)->toBe($this->value);
      });

      context('but overwritten', function () {
        it('returns the child’s value', function () {
          $this->node->parent()->addVariable('number', $this->value);
          $this->node->addVariable('number', $this->new_value);
          expect(subject()->number)->toBe($this->new_value);
        });
      });
    });
  });

  describe('#__set', function () {
    context('when cached', function () {
      it('returns true', function () {
        subject()->number;
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
