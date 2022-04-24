<?php

use CodingPaws\PSpec\Assert\Expectation;
use CodingPaws\PSpec\Coverage\Adapter;
use CodingPaws\PSpec\Coverage\XdebugAdapter;
use CodingPaws\PSpec\Executor;
use CodingPaws\PSpec\PSpec;

describe(Executor::class, function () {
  subject(fn () => new Executor(__DIR__ . '/../examples'));
  let('instance', PSpec::instance());

  after(function () {
    $this->instance->setAsInstance();
  });

  describe('#registerMatches', function () {
    it('registers matchers', function () {
      subject()->registerMatchers();

      $class = new ReflectionClass(Expectation::class);
      $matchers = $class->getProperty('matchers');
      $matchers->setAccessible(true);

      expect(array_keys($matchers->getValue()))->toBe([
        'toBe',
        'toBeCallable',
        'toThrow',
        'toContain',
        'toExtend',
        'toPrint',
      ]);
    });
  });

  describe('#registerCoverageAdapters', function () {
    it('registers the coverage adapters', function () {
      subject()->registerCoverageAdapters();

      $class = new ReflectionClass(Adapter::class);
      $adapters = $class->getProperty('adapters');
      $adapters->setAccessible(true);

      expect(array_keys($adapters->getValue()))->toBe([
        XdebugAdapter::class,
      ]);
    });
  });

  describe('#execute', function () {
    it('runs all tests', function () {
      ob_start();
      subject()->execute();
      $result = ob_get_clean();
      expect($result)->toContain("passed; \e[31m0\e[0m failed; finished in");
    });
  });
});
