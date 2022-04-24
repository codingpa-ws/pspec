<?php

use CodingPaws\PSpec\Console\TestFormatter;
use CodingPaws\PSpec\Stats;
use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\Spec\MockNode;

describe(TestFormatter::class, function () {
  subject(fn () => new MockTestFormatter);

  describe('#printResult', function () {
    let('stats', fn () => new Stats);

    it('prints the result text', function () {
      $this->stats->addTest(new TestResult(new MockNode, [], 10));
      $this->stats->addTest(new TestResult(new MockNode, [new Exception('exception 1')], 10));
      $this->stats->addTest(new TestResult(new MockNode, [new Exception('exception 2')], 10));

      expect(function () {
        subject()->printResult($this->stats, new DateTime);
      })
        ->toPrint('Exception: exception 1')
        ->toPrint('Exception: exception 2')
        ->toPrint("\e[32m1\e[0m passed; \e[31m2\e[0m failed; finished in");
    });

    context('without failures', function () {
      it('prints nothing', function () {
        expect(function () {
          subject()->printResult($this->stats, new DateTime);
        })->not->toPrint('Failures:');
      });
    });

    context('with failures', function () {
      let('exception', new Exception('Something went wrong here.'));
      before(function () {
        $this->stats->addTest(new TestResult(new MockNode, [$this->exception]));
      });

      it('prints the failures', function () {
        expect(function () {
          subject()->printResult($this->stats, new DateTime);
        })
          ->toPrint('Failures:')
          ->toPrint('Exception: ' . $this->exception->getMessage());
      });
    });
  });
});

class MockTestFormatter extends TestFormatter
{
  public function printTest(TestResult $result): void
  {
  }
}
