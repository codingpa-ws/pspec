<?php

use CodingPaws\PSpec\Console\TestFormatter;
use CodingPaws\PSpec\Stats;
use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\Spec\MockNode;

describe(TestFormatter::class, function () {
  subject(fn () => new MockTestFormatter);

  describe('#printResult', function () {
    let('stats', new Stats);
    it('prints the result text', function () {
      $this->stats->addTest(new TestResult(new MockNode, [], 10));
      $this->stats->addTest(new TestResult(new MockNode, [new Exception('exception 1')], 10));
      $this->stats->addTest(new TestResult(new MockNode, [new Exception('exception 2')], 10));

      expect(function () {
        subject()->printResult($this->stats, new DateTime());
      })
        ->toPrint('Exception: exception 1')
        ->toPrint('Exception: exception 2')
        ->toPrint("\e[32m1\e[0m passed; \e[31m2\e[0m failed; finished in");
    });
  });
});

class MockTestFormatter extends TestFormatter
{
  public function printTest(TestResult $result): void
  {
  }
}
