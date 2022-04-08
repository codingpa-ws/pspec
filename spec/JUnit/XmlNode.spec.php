<?php

use CodingPaws\PSpec\JUnit\XmlNode;

describe(XmlNode::class, function () {
  context('without children', function () {
    subject(fn () => new XmlNode('testsuites', [
      'name' => 'PSpec',
    ], 'example text'));

    it('generates a single node with text', function () {
      expect((string) $this->subject)->toBe('<testsuites name="PSpec">example text</testsuites>' . PHP_EOL);
    });

    it('has attributes', function () {
      expect($this->subject->getAttributes())->toBe([
        'name' => 'PSpec',
      ]);
    });
  });

  context('with children', function () {
    subject(fn () => new XmlNode('testsuites', [
      'name' => 'PSpec',
    ]));

    before(function () {
      $this->subject->add(new XmlNode('testsuite', [
        'name' => 'Doing magic',
        'tests' => 42,
        'time' => 1.23456789,
      ], 'child text'));
    });

    it('can add another node', function () {
      $this->subject->add(new XmlNode('p'));
      expect((string)$this->subject)->toContain('<p></p>');
    });

    it('generates a single node with text', function () {
      expect((string) $this->subject)->toBe(
        <<<xml
          <testsuites name="PSpec"><testsuite name="Doing magic" tests="42" time="1.23456789">child text</testsuite>
          </testsuites>\n
          xml
      );
    });

    it('has only the parent attributes', function () {
      expect($this->subject->getAttributes())->toBe([
        'name' => 'PSpec',
      ]);
    });
  });
});
