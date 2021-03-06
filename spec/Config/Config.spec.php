<?php

use CodingPaws\PSpec\Config\Config;
use CodingPaws\PSpec\Console\DotTestFormatter;
use CodingPaws\PSpec\Console\TestFormatter;
use CodingPaws\PSpec\Tree\TestResult;

describe(Config::class, function () {
  subject(fn () => Config::new());

  describe('::new', function () {
    it('creates a new config', function () {
      expect(Config::new()::class)->toBe(Config::class);
    });
  });

  describe('#addDirectory', function () {
    it('adds a directory if it exists', function () {
      $this->subject->addDirectory('../Config');
      expect($this->subject->getDirectories())->toContain(__DIR__ . '/../Config');
    });

    context('when the directory does not exist', function () {
      it('throws an exception', function () {
        expect(fn () => $this->subject->addDirectory('../Cfgs'))
          ->toThrow(RuntimeException::class);
      });
    });
  });

  describe('#getBasePath', function () {
    it('is the path where the config was created from', function () {
      expect($this->subject->getBasePath())->toBe(__DIR__);
    });
  });

  describe('#getDirectories', function () {
    context('when the spec directory exists', function () {
      let('path', __DIR__ . '/spec');
      before(fn () => mkdir(get('path')));
      after(fn () => rmdir(get('path')));

      it('has the spec directory by default', function () {
        $dirs = $this->subject->getDirectories();

        expect($dirs)->toBe([get('path')]);
      });
    });
  });

  describe('#setFormatter', function () {
    it('sets the formatter', function () {
      $this->subject->setFormatter(new ExampleFormatter);
      expect($this->subject->getFormatter()::class)->toBe(ExampleFormatter::class);
    });
  });

  describe('#getFormatter', function () {
    it('returns a DotTestFormatter by default', function () {
      expect($this->subject->getFormatter()::class)->toBe(DotTestFormatter::class);
    });
  });

  describe('JUnit file selection', function () {
    it('sets the JUnit file', function () {
      $this->subject->logJUnit('../tests/junit.xml');
      expect($this->subject->getJUnitFile())->toBe('../tests/junit.xml');
    });

    it('is null by default', function () {
      expect($this->subject->getJUnitFile())->toBe(null);
    });
  });
});

class ExampleFormatter extends TestFormatter
{
  public function printTest(TestResult $result): void
  {
  }
}
