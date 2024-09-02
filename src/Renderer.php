<?php

namespace Mariano\Randomgames;

class Renderer
{
    public function renderGameCode(string $gameCode)
    {
        echo $gameCode;
        exit;
    }

    public function renderLoadingPage()
    {
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Loading...</title>
            <style>
                body {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    margin: 0;
                    background: linear-gradient(135deg, #1e3c72, #2a5298);
                    font-family: "Arial", sans-serif;
                    color: #fff;
                }
                .loading-container {
                    text-align: center;
                    background: rgba(0, 0, 0, 0.8);
                    padding: 30px 50px;
                    border-radius: 15px;
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
                }
                .loading-container h1 {
                    font-size: 1.8rem;
                    margin-bottom: 10px;
                }
                .loading-container p {
                    font-size: 1rem;
                    color: #ddd;
                }
            </style>
        </head>
        <body>
            <div class="loading-container">
                <h1>No Unplayed Games Available</h1>
                <p>Generating a new game... Please wait a moment!</p>
            </div>
        </body>
        </html>';
    }
}
