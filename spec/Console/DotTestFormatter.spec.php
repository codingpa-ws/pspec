<?php

use CodingPaws\PSpec\Console\DotTestFormatter;
use CodingPaws\PSpec\Tree\DescribeNode;
use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\Spec\MockNode;

describe(DotTestFormatter::class, function () {
  describe('a group', function () {
    it('prints nothing', function () {
      expect(function () {
        $this->subject->printTest(new TestResult(new DescribeNode(new MockNode, 'foo')));
      })->toPrint('', exact: true);
    });
  });

  describe('a failure, than a success', function () {
    it('prints a dot and an F', function () {
      expect(function () {
        $this->subject->printTest(new TestResult(new MockNode));
        $this->subject->printTest(new TestResult(new MockNode, throwables: [new Exception]));
      })->toPrint("\e[32m.\e[31mF", exact: true);
    });
  });

  describe('a success, than a failure', function () {
    it('prints an F and a .', function () {
      expect(function () {
        $this->subject->printTest(new TestResult(new MockNode, throwables: [new Exception]));
        $this->subject->printTest(new TestResult(new MockNode));
      })->toPrint("\e[31mF\e[32m.", exact: true);
    });
  });

  describe('two consecutive identical states', function () {
    context('two successful tests', function () {
      it('prints one color and two dots', function () {
        expect(function () {
          $this->subject->printTest(new TestResult(new MockNode));
          $this->subject->printTest(new TestResult(new MockNode));
        })->toPrint("\e[32m..", exact: true);
      });
    });

    context('two successful tests', function () {
      it('prints one color and two Fs', function () {
        expect(function () {
          $this->subject->printTest(new TestResult(new MockNode, throwables: [new Exception]));
          $this->subject->printTest(new TestResult(new MockNode, throwables: [new Exception]));
        })->toPrint("\e[31mFF", exact: true);
      });
    });
  });
});
