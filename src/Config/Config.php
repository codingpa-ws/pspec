<?php

namespace CodingPaws\PSpec\Config;

use Exception;
use RuntimeException;

class Config
{
  private array $dirs = [];

  public static function new(): self
  {
    $basepath = dirname((new Exception)->getTrace()[0]['file']);
    return new self($basepath);
  }

  private function __construct(private string $basepath)
  {
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

  public function getBasePath(): string
  {
    return $this->basepath;
  }

  public function getDirectories(): array
  {
    return $this->dirs;
  }
}
