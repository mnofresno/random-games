<?php

namespace Mariano\Randomgames;

use Mariano\Randomgames\Environment;
use Mariano\Randomgames\LoggerFactory;
use Mariano\Randomgames\Renderer;
use Mariano\Randomgames\GameFileManager;
use Mariano\Randomgames\AssistanceDto;
use Mariano\Randomgames\CompletionAssistant;
use Monolog\Logger as MonologLogger; // Import Monolog Logger

class GameGenerator
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;
    private MonologLogger $logger; // Update the type to MonologLogger
    private string $userInput;
    private string $systemPromptContents;
    private GameFileManager $fileManager;
    private Renderer $renderer;

    public function __construct() {
        $environment = new Environment();
        $this->apiKey = $environment->getApiKey();
        $this->apiUrl = $environment->getApiUrl();
        $this->model = $environment->getModel();
        $this->systemPromptContents = $environment->getSystemPromptContents();

        $this->logger = (new LoggerFactory())->getLogger(); // Correctly assign MonologLogger instance
        $this->fileManager = new GameFileManager($this->logger);
        $this->renderer = new Renderer();
        $this->userInput = $_GET['user_input'] ?? $this->getRandomGame();

        $this->logger->debug("GameGenerator initialized with model: {$this->model}, API URL: {$this->apiUrl}");
    }

    public function generate(): string {
        $this->logger->info("Generating game with user input: {$this->userInput}");

        try {
            $gameFile = $this->fileManager->getUnplayedGame();

            if ($gameFile) {
                $this->logger->info("Unplayed game found: {$gameFile}");
                $this->fileManager->markGameAsPlayed($gameFile);
                $gameCode = $this->fileManager->readGameCode($gameFile);
                $this->renderer->renderGameCode($gameCode);
            } else {
                $this->logger->warning("No unplayed games found. Generating a new game...");
                $this->renderer->renderLoadingPage();
                $this->prepareForBackground();
                $this->logger->debug("Going background...");
                $this->finishRequest();
                $this->generateNewGame();
            }
        } catch (\Exception $e) {
            $this->logger->error("An error occurred during game generation: " . $e->getMessage());
            error_log("Exception in GameGenerator::generate(): " . $e->getMessage());
        }
    }

    private function prepareForBackground(): void {
        ignore_user_abort(true);
        set_time_limit(0);
    }

    private function finishRequest(): void {
        if (function_exists('fastcgi_finish_request')) {
            $this->logger->debug('Finishing request...');
            session_write_close();

            fastcgi_finish_request();
            $this->logger->info('Request finished OK, continue working on background');
        } else {
            $this->logger->error('fatcgi_finish_request function not found');
        }
    }

    private function generateNewGame() {
        $this->logger->info("Starting new game generation...");
        error_log("Generating a new game...");

        try {
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
                $this->logger->info("New game code generated successfully.");
                $this->fileManager->saveGame($gameCode);
            } else {
                $this->logger->error("Failed to generate game code.");
            }
        } catch (\Exception $e) {
            $this->logger->error("An error occurred while generating a new game: " . $e->getMessage());
            error_log("Exception in GameGenerator::generateNewGame(): " . $e->getMessage());
        }
    }

    private function getRandomGame(): string {
        $classicGames = ['snake', 'tetris', 'pong', 'pacman', 'space invaders', 'asteroids', 'breakout'];
        $randomGame = $classicGames[array_rand($classicGames)];
        $this->logger->debug("Random game selected: {$randomGame}");
        return $randomGame;
    }
}
