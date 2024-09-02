<?php

namespace Mariano\Randomgames;

use Monolog\Logger;

class GameFileManager
{
    private string $gamesDirectory;
    private string $playedGamesFile;
    private Logger $logger;

    public function __construct(Logger $logger) {
        $this->gamesDirectory = __DIR__ . '/../public/games/';
        $this->playedGamesFile = __DIR__ . '/../already_played_games.txt';
        $this->logger = $logger;
    }

    public function getUnplayedGame(): ?string {
        $playedGames = file_exists($this->playedGamesFile) ? file($this->playedGamesFile, FILE_IGNORE_NEW_LINES) : [];
        $allGames = glob($this->gamesDirectory . '*-*.html');

        foreach ($allGames as $gamePath) {
            $gameFile = basename($gamePath);
            if (!in_array($gameFile, $playedGames)) {
                return $gameFile;
            }
        }

        return null;
    }

    public function markGameAsPlayed(string $gameFile) {
        file_put_contents($this->playedGamesFile, $gameFile . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function readGameCode(string $gameFile): string {
        return file_get_contents($this->gamesDirectory . $gameFile);
    }

    public function saveGame(string $gameCode) {
        $gameName = $this->extractName($gameCode);
        $uniqueId = uniqid();
        $fileName = "{$gameName}-{$uniqueId}.html";
        $filePath = $this->gamesDirectory . $fileName;

        if (!file_exists($this->gamesDirectory)) {
            $this->logger->error("Creating games dir");
            mkdir($this->gamesDirectory, 0777, true);
        }

        if (file_put_contents($filePath, $gameCode) === false) {
            $this->logger->error("Failed to write game code to file: " . $filePath);
        } else {
            $this->logger->info("Game code successfully written to: " . $filePath);
        }
    }

    private function extractName(string $code): string {
        preg_match('/<!--- game code: ([^}]+) --->/', $code, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return 'unknown_game';
    }
}
