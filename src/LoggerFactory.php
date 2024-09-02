<?php

namespace Mariano\Randomgames;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

class LoggerFactory {
    private MonologLogger $logger;

    public function __construct() {
        $this->logger = new MonologLogger('randomgames_logger');
        $this->logger->pushHandler(new StreamHandler('php://stdout', MonologLogger::DEBUG));
        $this->logger->pushHandler(new StreamHandler('php://stderr', MonologLogger::ERROR));
    }

    public function getLogger(): MonologLogger {
        return $this->logger;
    }
}
