<?php

use CodingPaws\PSpec\Tree\RootNode;

describe(RootNode::class, function () {
  subject(fn () => new RootNode());

  describe('#name', function () {
    it('returns an empty string', function () {
      expect(subject()->name())->toBe('');
    });
  });
});
