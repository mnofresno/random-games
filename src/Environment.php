<?php

namespace Mariano\Randomgames;

use Dotenv\Dotenv;

class Environment {
    private string $apiKey;
    private string $apiUrl;
    private string $model;
    private string $systemPromptContents;

    public function __construct() {
        $cwd = getcwd();
        $dotenv = Dotenv::createImmutable("$cwd/..");
        $dotenv->load();

        $this->apiKey = $_ENV['OPENAI_API_KEY'];
        $this->apiUrl = $_ENV['BASE_URL'] ?: 'https://api.openai.com/v1';
        $this->model = $_ENV['MODEL'] ?: 'gpt-4o-mini';
        $this->systemPromptContents = file_get_contents(__DIR__ . '/../instructions_prompt.md');
    }

    public function getApiKey(): string {
        return $this->apiKey;
    }

    public function getApiUrl(): string {
        return $this->apiUrl;
    }

    public function getModel(): string {
        return $this->model;
    }

    public function getSystemPromptContents(): string {
        return $this->systemPromptContents;
    }
}
