<?php

namespace CodingPaws\PSpec\Tree;

use CodingPaws\PSpec\PSpec;

class Tree
{
  private Node $root;

  public function __construct(
    private PSpec $app,
  ) {
    $this->root = new RootNode;
  }

  public function getRoot(): Node
  {
    return $this->root;
  }

  public function setRoot(Node $root): void
  {
    $this->root = $root;
  }
}
