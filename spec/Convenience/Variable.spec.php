<?php

use CodingPaws\PSpec\Convenience\Variable;

describe(Variable::class, function () {
  let('actual_value', 1234);
  subject(new Variable('test', 1234));
  let('value', fn () => get('actual_value'));

  describe('with explicit value', function () {
    it('#getName', function () {
      expect(get('subject')->getName())->toBe('test');
    });

    it('#computeValue', function () {
      expect(get('subject')->computeValue())->toBe(get('actual_value'));
    });
  });

  describe('with implicit value', function () {
    let('value', fn () => fn () => get('actual_value'));

    it('#getName', function () {
      expect(get('subject')->getName())->toBe('test');
    });

    it('#computeValue', function () {
      expect(get('subject')->computeValue())->toBe(get('actual_value'));
    });
  });
});
