<?php

use CodingPaws\PSpec\JUnit\JUnitGenerator;
use CodingPaws\PSpec\Tree\DescribeNode;
use CodingPaws\PSpec\Tree\TestResult;
use CodingPaws\Spec\MockNode;

describe(JUnitGenerator::class, function () {
  let('file', fn () => tempnam(sys_get_temp_dir(), 'junit'));
  subject(fn () => new JUnitGenerator(get('file')));
  let('exception', new Exception('Something went wrong'));
  let('result', function () {
    subject()->execute(new DateTime('2020-01-01T00:00:00+00:00'));

    return file_get_contents(get('file'));
  });

  before(function () {
    $node = new MockNode();

    $this->subject->addResult(new TestResult(
      new MockNode($node),
      throwables: [$this->exception],
      milliseconds: 1394.53,
    ));
  });

  it('generates a single test result for one test', function () {
    expect($this->result)->toContain('<testsuites name="PSpec" tests="1" time="');
    expect($this->result)->toContain('timestamp="2020-01-01T00:00:00+00:00" failures="1"><testsuite name="MockNode"><testcase name="MockNode" time="1.39453000"><failure message="Something went wrong" type="Exception">Exception: Something went wrong in');
  });

  context('with another successful test', function () {
    it('only increments the tests count', function () {
      $node = new MockNode();

      $this->subject->addResult(new TestResult(
        new MockNode($node),
        throwables: [],
        milliseconds: 1394.53,
      ));

      expect($this->result)->toContain('failures="1"');
      expect($this->result)->toContain('tests="2"');
    });
  });

  context('with a group node added', function () {
    it('ignores it', function () {
      $node = new MockNode();

      $this->subject->addResult(new TestResult(
        new DescribeNode($node, 'test group'),
        milliseconds: 1394.53,
      ));

      expect($this->result)->toContain('failures="1"');
      expect($this->result)->toContain('tests="1"');
      expect($this->result)->not->toContain('test group');
    });
  });
});
