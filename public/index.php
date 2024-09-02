<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mariano\Randomgames\GameGenerator;

$generator = new GameGenerator();
$generator->generate();
