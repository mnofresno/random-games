#!/bin/bash

set -e  # Exit immediately if a command exits with a non-zero status.

# Define variables
IMAGE_NAME="ghcr.io/mnofresno/random-games:0.0.1"
CONTAINER_NAME="random-games"
PROJECT_DIR=$(pwd)
ENV_FILE="${PROJECT_DIR}/.env"
PLAYED_GAMES_FILE="${PROJECT_DIR}/already_played_games.txt"

# Ensure Composer is installed
if ! [ -x "$(command -v composer)" ]; then
  echo "Error: Composer is not installed." >&2
  exit 1
fi

# Install Composer dependencies
if [ ! -d "${PROJECT_DIR}/vendor" ]; then
  echo "Installing Composer dependencies..."
  composer install
else
  echo "Composer dependencies already installed."
fi

# Set necessary permissions for files and directories
echo "Setting file and directory permissions..."
chmod 666 "${PLAYED_GAMES_FILE}"
chmod -R 777 "${PROJECT_DIR}/public/games"
chmod -R 777 "${PROJECT_DIR}/logs"

# Check if Docker image exists; if not, build it
if [[ "$(docker images -q $IMAGE_NAME 2> /dev/null)" == "" ]]; then
  echo "Docker image not found. Building the Docker image..."
  docker build -t $IMAGE_NAME -f docker/Dockerfile .
else
  echo "Docker image $IMAGE_NAME already exists."
fi

# Stop and remove any existing container with the same name
if [ "$(docker ps -aq -f name=$CONTAINER_NAME)" ]; then
  echo "Stopping and removing existing container..."
  docker stop $CONTAINER_NAME
  docker rm $CONTAINER_NAME
fi

# Ensure the .env file exists
if [ ! -f "$ENV_FILE" ]; then
  echo "Error: .env file not found in the project directory. Please create it before running the script."
  exit 1
fi

# Run the Docker container with the project directory mounted as a volume
echo "Running the Docker container..."
docker run -d -p 9999:80 --name $CONTAINER_NAME \
  -v $PROJECT_DIR:/var/www/html:rw \
  $IMAGE_NAME

echo "Container is running. Access the game at http://localhost:9999"

# Display Docker logs for troubleshooting (optional)
echo "Displaying Docker logs (Press Ctrl+C to stop viewing logs)..."
docker logs -f $CONTAINER_NAME
