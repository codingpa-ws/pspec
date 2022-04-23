<?php

use CodingPaws\PSpec\Console\DocTestFormatter;
use CodingPaws\PSpec\Tree\DescribeNode;
use CodingPaws\PSpec\Tree\TestNode;
use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\Spec\MockNode;

describe(DocTestFormatter::class, function () {
  let('group_title', 'A component');
  let('group', fn () => new DescribeNode(new MockNode, get('group_title')));

  describe('a group', function () {
    it('prints the relative group title', function () {
      expect(function () {
        $this->subject->printTest(new TestResult($this->group));
      })->toPrint($this->group_title . PHP_EOL, exact: true);
    });
  });

  describe('a successful test', function () {
    it('prints a green checkmark, followed by the name', function () {
      expect(function () {
        $test = new TestNode($this->group, 'does something', fn () => null);
        $this->subject->printTest(new TestResult($test));
      })->toPrint("\e[32m✔ \e[0mdoes something\n", exact: true);
    });
  });

  describe('a failing test', function () {
    it('prints a red cross, followed by the name', function () {
      expect(function () {
        $test = new TestNode($this->group, 'does something else', fn () => null);
        $test->setIndent("    ");
        $this->subject->printTest(new TestResult($test, [new Exception]));
      })->toPrint("    \e[31m✘ \e[0mdoes something else\n", exact: true);
    });
  });
});
