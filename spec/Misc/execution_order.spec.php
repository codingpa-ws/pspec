<?php

$GLOBALS['i'] = 0;

describe('execution order', function () {
  expect(++$GLOBALS['i'])->toBe(1);

  before(function () {
    expect(++$GLOBALS['i'])->toBe(4);
  });

  before(function () {
    expect(++$GLOBALS['i'])->toBe(5);
  });

  after(function () {
    expect(++$GLOBALS['i'])->toBe(9);
  });

  describe('describe 2', function () {
    expect(++$GLOBALS['i'])->toBe(2);
    context('empty context', fn () => null);

    after(function () {
      expect(++$GLOBALS['i'])->toBe(7);
    });

    after(function () {
      expect(++$GLOBALS['i'])->toBe(8);
    });

    context('full context', function () {
      expect(++$GLOBALS['i'])->toBe(3);
      it('inner test case', function () {
        expect(++$GLOBALS['i'])->toBe(6);
      });
    });
  });
});
