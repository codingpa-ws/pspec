<?php

use CodingPaws\PSpec\Tree\DescribeNode;
use CodingPaws\Spec\MockApp;
use CodingPaws\Spec\MockNode;

describe(DescribeNode::class, function () {
  let('title', 'Example group');
  let('parent_node', fn () => new MockNode());
  let('mock_app', fn () => new MockApp());
  subject(fn () => new DescribeNode(get('parent_node'), get('title')));

  describe('#run', function () {
    it('prints on the app', function () {
      subject()->run($this->mock_app);

      $prints = $this->mock_app->getPrints();
      expect(count($prints))->toBe(1);
      expect($prints[0]->getNode())->toBe(subject());
    });

    context('with tests', function () {
      let('example_test', new MockNode());

      before(function () {
        subject()->children[] = $this->example_test;
      });

      it('runs tests on all child nodes', function () {
        expect($this->example_test->getRuns())->toBe(0);

        subject()->run($this->mock_app);

        expect($this->example_test->getRuns())->toBe(1);
      });

      it('prints the group and test', function () {
        subject()->run($this->mock_app);

        $prints = $this->mock_app->getPrints();
        expect(count($prints))->toBe(2);
      });
    });
  });

  describe('#name', function () {
    it('returns the title', function () {
      expect(subject()->name())->toBe($this->title);
    });
  });
});
