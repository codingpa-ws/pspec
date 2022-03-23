<?php

use CodingPaws\PSpec\Config\Config;

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
});
