<?php

namespace CodingPaws\PSpec\Coverage;

abstract class Adapter
{
  /**
   * Starts the code coverage collector
   */
  abstract public function startTest(): void;

  /**
   * Stops the code coverage collector
   */
  abstract public function endTest(): void;

  /**
   * Computes the total coverage and returns a float
   * between 0 and 1 for 0% to 100% line coverage.
   */
  abstract public function computeTotalCoverage(): float;

  /**
   * Computes the line coverage keyed by the file name.
   *
   * Each array value is a float between 0 and 1 for 0%
   * to 100% line coverage.
   */
  abstract public function computeCoverageByFile(): array;

  /**
   * Returns true if the adapter’s underlying system
   * (e.g. XDebug) is enabled and available.
   *
   * Only available adapters will be considered for
   * actually collecting code coverage by the
   * framework.
   */
  abstract public function available(): bool;

  private static array $adapters = [];

  /**
   * Register a new code coverage adapater/collector.
   */
  public static function register(Adapter $adapter): void
  {
    self::$adapters[] = $adapter;
  }

  /**
   * Get the currently available code coverage adapater.
   *
   * It returns the first registered and available one.
   */
  public static function get(): ?Adapter
  {
    foreach (self::$adapters as $adapter) {
      if ($adapter->available()) {
        return $adapter;
      }
    }

    return null;
  }
}
