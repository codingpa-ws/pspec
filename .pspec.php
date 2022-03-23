<?php

use CodingPaws\PSpec\Config\Config;
use CodingPaws\PSpec\Console\DocTestFormatter;
use CodingPaws\PSpec\Console\DotTestFormatter;

return Config::new()
  ->addDirectory('examples')
  ->setFormatter(getenv('CI') ? new DocTestFormatter : new DotTestFormatter);
