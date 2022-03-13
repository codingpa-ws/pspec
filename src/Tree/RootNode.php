<?php

namespace CodingPaws\PSpec\Tree;

class RootNode extends Node
{
  public function __construct()
  {
    parent::__construct();
  }

  public function name(): string
  {
    return "";
  }
}
