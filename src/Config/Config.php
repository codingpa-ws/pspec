<?php

namespace CodingPaws\PSpec\Config;

use CodingPaws\PSpec\Console\DotTestFormatter;
use CodingPaws\PSpec\Console\TestFormatter;
use Exception;
use RuntimeException;

class Config
{
  private array $dirs = [];
  private TestFormatter $formatter;

  public static function new(): self
  {
    $basepath = dirname((new Exception)->getTrace()[0]['file']);
    return new self($basepath);
  }

  private function __construct(private string $basepath)
  {
    $this->formatter = new DotTestFormatter;
    try {
      $this->addDirectory('spec');
    } catch (\Throwable $th) {
      // Do nothing, this is only a convenience thing
    }
  }

  public function addDirectory(string $dir): self
  {
    $full_path = $this->basepath . DIRECTORY_SEPARATOR . $dir;

    if (!is_dir($full_path)) {
      throw new RuntimeException("Path $full_path is not a directory");
    }

    $this->dirs[] = $full_path;

    return $this;
  }

  public function setFormatter(TestFormatter $formatter): self
  {
    $this->formatter = $formatter;

    return $this;
  }

  public function getFormatter(): TestFormatter
  {
    return $this->formatter;
  }

  public function getBasePath(): string
  {
    return $this->basepath;
  }

  public function getDirectories(): array
  {
    return $this->dirs;
  }
}
