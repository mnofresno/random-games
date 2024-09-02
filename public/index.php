<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Mariano\Randomgames\GameGenerator;

$generator = new GameGenerator();
$response = $generator->generate();

echo "<html><head><title>Generated Game Code</title></head><body>";
echo "<h1>Generated Game Code:</h1>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";
echo "</body></html>";
