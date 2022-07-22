<?php

use CodingPaws\PSpec\Pipeline;

describe(Pipeline::class, function () {
  it('runs all pipes in the correct order', function () {
    $pipeline = $this->subject
      ->send(function () {
        echo ' test ';
      })
      ->pipeAll([
        function ($test) {
          echo '+1';
          $test();
          echo '-1';
        }, function ($test) {
          echo '+2';
          $test();
          echo "-2";
        },
      ]);

    expect(fn () => $pipeline->run())->toPrint('+1+2 test -2-1', exact: true);
  });

  context('if an error is reported', function () {
    let('exception', new Exception('An example error occured'));

    it('returns the error', function () {
      $errors = $this->subject
        ->send(fn () => null)
        ->pipeAll([
          function ($test, $report) {
            $test();
            $report(get('exception'));
          }, function ($test) {
            $test();
          },
        ])->run();

      expect($errors)->toBe([$this->exception]);
    });
  });
});
