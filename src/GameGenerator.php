<?php

namespace Mariano\Randomgames;

use Mariano\Randomgames\AssistanceDto;
use Mariano\Randomgames\CompletionAssistant;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class GameGenerator
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;
    private Logger $logger;
    private string $userInput;
    private string $systemPromptContents;
    private string $gamesDirectory;
    private string $playedGamesFile;

    public function __construct() {
        $cwd = getcwd();
        $dotenv = Dotenv::createImmutable("$cwd");
        $dotenv->load();

        $this->apiKey = $_ENV['OPENAI_API_KEY'];
        $this->apiUrl = $_ENV['BASE_URL'] ?: 'https://api.openai.com/v1';
        $this->model = $_ENV['MODEL'] ?: 'gpt-4o-mini';

        $this->logger = new Logger('completion_assistant');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::DEBUG));

        $this->systemPromptContents = file_get_contents(__DIR__ . '/../instructions_prompt.md');
        $this->gamesDirectory = __DIR__ . '/../public/games/';
        $this->playedGamesFile = __DIR__ . '/../already_played_games.txt';

        $this->userInput = $_GET['user_input'] ?? $this->getRandomGame(); // Use random game if no user input
    }

    public function generate() {
        $gameFile = $this->getUnplayedGame();

        if ($gameFile) {
            $this->markGameAsPlayed($gameFile);
            $gameCode = file_get_contents($this->gamesDirectory . $gameFile);
            $this->renderGameCode($gameCode);
        } else {
            echo "No unplayed games available. Generating a new game...";
            $this->generateNewGame();
        }

        fastcgi_finish_request(); // End the request here, new game will be generated in the background

        $this->generateNewGame(); // Generate a new game in the background
    }

    private function getUnplayedGame(): ?string {
        $playedGames = file_exists($this->playedGamesFile) ? file($this->playedGamesFile, FILE_IGNORE_NEW_LINES) : [];
        $allGames = glob($this->gamesDirectory . '*-*.html');

        foreach ($allGames as $gamePath) {
            $gameFile = basename($gamePath);
            if (!in_array($gameFile, $playedGames)) {
                return $gameFile;
            }
        }

        return null; // No unplayed games available
    }

    private function markGameAsPlayed(string $gameFile) {
        file_put_contents($this->playedGamesFile, $gameFile . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private function generateNewGame() {
        $dto = new AssistanceDto(
            $this->apiUrl,
            $this->apiKey,
            $this->userInput,
            $this->systemPromptContents,
            $this->model
        );

        $completionAssistant = new CompletionAssistant($this->logger);
        $gameCode = $completionAssistant->assist($dto);

        if ($gameCode) {
            $gameName = $this->extractName($gameCode);
            $uniqueId = uniqid();
            $fileName = "{$gameName}-{$uniqueId}.html";
            $filePath = $this->gamesDirectory . $fileName;

            if (!file_exists($this->gamesDirectory)) {
                mkdir($this->gamesDirectory, 0777, true);
            }

            file_put_contents($filePath, $gameCode);
        } else {
            $this->logger->error("Failed to generate game code.");
        }
    }

    private function extractName(string $code): string {
        preg_match('/<!--- game code: ([^}]+) --->/', $code, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return 'unknown_game';
    }

    private function getRandomGame(): string {
        $classicGames = ['snake', 'tetris', 'pong', 'pacman', 'space invaders', 'asteroids', 'breakout'];
        return $classicGames[array_rand($classicGames)];
    }

    private function renderGameCode(string $gameCode) {
        echo $gameCode;
        exit;
    }
}
