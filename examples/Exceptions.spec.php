<?php

describe('Exception handing', function () {
  it('catches exceptions', function () {
    expect(function () {
      throw new TypeError();
    })->toThrow(TypeError::class);
  });

  it('specifies explicitly that no exception was thrown', function () {
    expect(function () {
    })->toThrow(null);
  });
});
