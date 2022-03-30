<?php

use CodingPaws\PSpec\Assert\Expectation;
use CodingPaws\PSpec\Coverage\Adapter;
use CodingPaws\PSpec\Coverage\XDebugAdapter;
use CodingPaws\PSpec\Executor;
use CodingPaws\PSpec\PSpec;

describe(Executor::class, function () {
  subject(fn () => new Executor(__DIR__ . '/../examples'));

  before(function () {
    $GLOBALS['PSPEC_ALLOW_MULTIPLE_INSTANCES'] = true;

    $pspec = new ReflectionClass(PSpec::class);
    $instance = $pspec->getProperty('instance');
    $instance->setAccessible(true);
    $GLOBALS['PSPEC_INSTANCE'] = $instance->getValue();
  });

  after(function () {
    unset($GLOBALS['PSPEC_ALLOW_MULTIPLE_INSTANCES']);

    $pspec = new ReflectionClass(PSpec::class);
    $instance = $pspec->getProperty('instance');
    $instance->setAccessible(true);

    $instance->setValue($GLOBALS['PSPEC_INSTANCE']);
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
        XDebugAdapter::class,
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
