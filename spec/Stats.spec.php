<?php

use CodingPaws\PSpec\Stats;
use CodingPaws\PSpec\Tree\RootNode;

describe(Stats::class, function () {
  subject(fn () => new Stats);
  let('node', fn () => new RootNode);
  let('exception', new Exception('test message'));

  describe("#addTest", function () {
    it('adds a test', function () {
      subject()->addTest($this->node);

      expect(subject()->countPasses())->toBe(1);
      expect(subject()->countFailures())->toBe(0);
    });

    context('with an error', function () {
      it('adds a test with a failure', function () {
        subject()->addTest($this->node, $this->exception);

        expect(subject()->countPasses())->toBe(0);
        expect(subject()->countFailures())->toBe(1);
      });
    });
  });

  describe('#merge', function () {
    it('returns a new, merged Stats object', function () {
      $other_stats = new Stats;
      $other_stats->addTest($this->node, $this->exception);
      subject()->addTest($this->node);
      $new_stats = subject()->merge($other_stats);

      expect($other_stats->countPasses())->toBe(0);
      expect($other_stats->countFailures())->toBe(1);
      expect(subject()->countPasses())->toBe(1);
      expect(subject()->countFailures())->toBe(0);

      expect($new_stats->countPasses())->toBe(1);
      expect($new_stats->countFailures())->toBe(1);
    });
  });

  describe('#printFailures', function () {
    context('without failures', function () {
      it('prints nothing', function () {
        ob_start();
        subject()->printFailures();
        expect(ob_get_clean())->toBe('');
      });
    });

    context('with failures', function () {
      it('prints the failures', function () {
        subject()->addTest($this->node, $this->exception);

        ob_start();
        subject()->printFailures();
        expect(ob_get_clean())->toContain('Exception: ' . $this->exception->getMessage());
      });
    });
  });

  describe('#countAll', function () {
    it('returns the number of all tests', function () {
      subject()->addTest($this->node);
      subject()->addTest($this->node);
      subject()->addTest($this->node, $this->exception);

      expect(subject()->countAll())->toBe(3);
    });
  });
});
