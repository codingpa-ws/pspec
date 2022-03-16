<?php

describe('expect()->not', function () {
  context('toBe', function () {
    it('passes', function () {
      expect(1)->not->toBe(2);
      expect(1)->toBe(1);
    });
  });

  context('toBeCallable', function () {
    it('passes', function () {
      expect(fn () => 1)->toBeCallable();
      expect(1)->not->toBeCallable();
    });
  });

  context('toThrow', function () {
    it('passes', function () {
      expect(fn () => 1)->not->toThrow();
      expect(function () {
        throw new RuntimeException;
      })->toThrow(RuntimeException::class);
    });
  });
});
