<?php

class Example
{
  public function getName(): string
  {
    return "simple example";
  }
}

describe(Example::class, function () {
  describe('#getName', function () {
    it('returns its name', function () {
      expect(subject()->getName())->toBe('simple example');
    });
  });
});
