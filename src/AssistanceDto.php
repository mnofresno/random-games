<?php

namespace Mariano\Randomgames;

class AssistanceDto {
    private ?string $model;
    private string $apiUrl;
    private string $apiKey;
    private string $userInput;
    private string $systemPromptContents;

    public function __construct(
        string $apiUrl,
        string $apiKey,
        string $userInput,
        string $systemPromptContents,
        ?string $model = "gpt-4o-mini"
    ) {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
        $this->userInput = $userInput;
        $this->systemPromptContents = $systemPromptContents;
        $this->model = $model;
    }

    public function model(): ?string {
        return $this->model;
    }

    public function apiUrl(): string {
        return $this->apiUrl;
    }

    public function apiKey(): string {
        return $this->apiKey;
    }

    public function userInput(): string {
        return $this->userInput;
    }

    public function systemPromptContents(): string {
        return $this->systemPromptContents;
    }
}
