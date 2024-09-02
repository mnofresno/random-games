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

# Ensure necessary files and directories exist and set permissions on the host
echo "Setting up necessary files and directories with correct permissions on the host..."

if [ ! -f "${PLAYED_GAMES_FILE}" ]; then
  echo "Creating ${PLAYED_GAMES_FILE}..."
  touch "${PLAYED_GAMES_FILE}"
fi
chmod 666 "${PLAYED_GAMES_FILE}"

if [ ! -d "${PROJECT_DIR}/public/games" ]; then
  echo "Creating public/games directory..."
  mkdir -p "${PROJECT_DIR}/public/games"
fi

# Ensure the .env file exists
if [ ! -f "$ENV_FILE" ]; then
  echo "Error: .env file not found in the project directory. Please create it before running the script."
  exit 1
fi

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

# Run the Docker container with the project directory and .env file mounted as volumes
echo "Running the Docker container..."
docker run -d -p 9999:80 --name $CONTAINER_NAME \
  -e XDEBUG_MODE \
  -v $PROJECT_DIR:/var/www/html:rw \
  -v $ENV_FILE:/var/www/html/.env:ro \
  $IMAGE_NAME

# Ensure correct permissions inside the container
echo "Setting permissions inside the Docker container..."
docker exec $CONTAINER_NAME sh -c 'chmod -R 777 /var/www/html/public/games && chmod 666 /var/www/html/already_played_games.txt'

echo "Container is running. Access the game at http://localhost:9999"

# Display Docker logs for troubleshooting (optional)
echo "Displaying Docker logs (Press Ctrl+C to stop viewing logs)..."
docker logs -f $CONTAINER_NAME
