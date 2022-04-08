<?php

namespace CodingPaws\PSpec\JUnit;

class XmlNode
{
  private array $children = [];

  public function __construct(
    private string $type,
    private array $attributes = [],
    private string $text = '',
  ) {
  }

  public function add(self $node): void
  {
    $this->children[] = $node;
  }

  public function getAttributes(): array
  {
    return $this->attributes;
  }

  public function __toString()
  {
    return "<$this->type" .
      implode('', array_map(function ($key, $value) {
        $key = htmlspecialchars($key);
        $value = htmlspecialchars($value);

        return " $key=\"$value\"";
      }, array_keys($this->attributes), $this->attributes)) .
      '>' .
      implode('', array_map(function ($node) {
        return (string) $node;
      }, $this->children)) .
      "$this->text</$this->type>\n";
  }
}
