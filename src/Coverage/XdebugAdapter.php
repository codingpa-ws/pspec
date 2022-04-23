<?php

namespace CodingPaws\PSpec\Coverage;

class XdebugAdapter extends Adapter
{
  public function startTest(): void
  {
    xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
  }

  public function endTest(): void
  {
    xdebug_stop_code_coverage(false);
  }

  public function computeTotalCoverage(): float
  {
    [$covered_lines, $all_lines] = array_reduce($this->computeCoverage(), function ($prev, $curr) {
      $prev[0] += $curr[0];
      $prev[1] += $curr[1];
      return $prev;
    }, [0, 0]);

    return $covered_lines / $all_lines;
  }

  public function computeCoverageByFile(): array
  {
    return array_map(
      function ($value) {
        if (!$value[1]) {
          return 1;
        }
        return $value[0] / $value[1];
      },
      $this->computeCoverage()
    );
  }

  private function computeCoverage(): array
  {
    $cov = xdebug_get_code_coverage();
    $cov = array_filter($cov, function ($value, $key) {
      return !str_contains($key, '/vendor/') && !str_ends_with($key, '.spec.php') && !str_ends_with($key, DIRECTORY_SEPARATOR . '.pspec.php');
    }, ARRAY_FILTER_USE_BOTH);

    return array_map(function ($value) {
      $value = array_filter($value, fn ($x) => $x !== -2);
      $total = count($value);
      $touched = count(array_filter($value, fn ($x) => $x === 1));

      if ($total === 0) {
        return;
      }

      return [$touched, $total];
    }, $cov);
  }

  public function available(): bool
  {
    return function_exists('xdebug_start_code_coverage')
      && function_exists('xdebug_stop_code_coverage')
      && function_exists('xdebug_get_code_coverage')
      && in_array('coverage', xdebug_info('mode'));
  }
}
