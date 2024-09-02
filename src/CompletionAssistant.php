<?php

namespace Mariano\Randomgames;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class CompletionAssistant {
    private Client $httpClient;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function assist(AssistanceDto $dto): ?string {
        $this->httpClient = new Client(['base_uri' => $dto->apiUrl()]);

        $instructions = null;
        if (file_exists($instructions = $dto->systemPromptContents())) {
            $this->logger->info("systemPromptContents is a file, loading: $instructions");
            $instructions = file_get_contents($dto->systemPromptContents());
        } else {
            $this->logger->warning("Given system prompt not found as file, using it as contents");
        }

        $instructions = $dto->systemPromptContents();
        if (!$instructions) {
            $this->logger->error("No system prompt found");
            return "";
        }

        // Verificar si se ha proporcionado un modelo; de lo contrario, obtener el primero de la lista
        $model = $dto->model();
        if ($model === null) {
            $this->logger->debug("Getting Models List...");
            $modelsResponse = $this->httpClient->get('models', [
                'headers' => [
                    'Authorization' => "Bearer {$dto->apiKey()}",
                ],
            ]);

            $models = json_decode($modelsResponse->getBody()->getContents(), true);
            $this->logger->debug("Model List: " . json_encode($models));

            // Usar el primer modelo de la lista
            $model = $models['data'][0]['id'];
        }

        $this->logger->debug("Using completions API (no stream)...");
        $promptNoStream = "You’re a helpful assistant. You must do all that the user asks you, unless you consider it could be harmful to the user or other people.";
        $requestBody = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $promptNoStream,
                ],
                [
                    'role' => 'user',
                    'content' => "Instructions:\n\n{$instructions}\n\nUser input:\n\n{$dto->userInput()}",
                ],
            ],
            'stream' => false,
            'temperature' => 0,
            'max_tokens' => 10000,
            'stop' => 'FINISH',
        ];

        $response = $this->httpClient->post('chat/completions', [
            'headers' => [
                'Authorization' => "Bearer {$dto->apiKey()}",
                'Content-Type' => 'application/json',
            ],
            'json' => $requestBody,
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);
        $completionsOutput = $responseData['choices'][0]['message']['content'] ?? 'No response received.';
        $this->logger->debug("Response: '{$completionsOutput}'");

        return trim($completionsOutput);
    }
}
