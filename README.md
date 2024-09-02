# Random Game Generator

This project is a PHP application that generates and serves classic games like Snake, Tetris, Pong, Pacman, Space Invaders, Asteroids, Breakout, etc. The application uses the OpenAI API to dynamically generate new game code and displays previously generated games in a web interface.

## Features

- Generates random classic games using the OpenAI API.
- Stores generated games in a local folder (`public/games/`).
- Serves a random pre-generated game on each page load that hasn't been played yet.
- Tracks played games in a text file (`already_played_games.txt`).
- Generates new games in the background to ensure there's always a fresh game available.

## Project Structure

```
.
├── already_played_games.txt
├── composer.json
├── composer.lock
├── .env
├── .gitignore
├── instructions_prompt.md
├── logs/
│   └── app.log
├── public/
│   ├── games/
│   └── index.php
├── src/
│   ├── AssistanceDto.php
│   ├── CompletionAssistant.php
│   └── GameGenerator.php
└── Dockerfile
```

## Requirements

- PHP 7.4 or higher
- Composer
- Docker

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/randomgames.git
   cd randomgames
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Configure environment variables:
   Create a `.env` file in the root directory and add your OpenAI API key and other configurations:
   ```plaintext
   OPENAI_API_KEY=your_openai_api_key
   BASE_URL=https://api.openai.com/v1
   MODEL=gpt-4o-mini
   ```

4. Run the application:
   ```bash
   php -S localhost:9999 -t public/
   ```

5. Access the application:
   Open your browser and go to `http://localhost:9999`.

## Docker Setup

1. Build the Docker image:
   ```bash
   docker build -t randomgames-app .
   ```

2. Run the Docker container:
   ```bash
   docker run -d -p 9999:80 --name randomgames-app randomgames-app
   ```

3. Access the application:
   Open your browser and go to `http://localhost:9999`.

## Notes

- The application will generate new games in the background using `fastcgi_finish_request()`.
- Regularly clean up the `logs/` and `public/games/` directories to prevent excessive storage usage.

## Contributing

Fork this repository and submit a pull request.

## License

This project is licensed under the MIT License.
