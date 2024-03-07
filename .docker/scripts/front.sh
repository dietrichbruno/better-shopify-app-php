#!/usr/bin/.env bash
source $(dirname "$0")/colors.sh

echo -e $BLUE_COLOR"Running vite server..."$BLUE_COLOR
npm run --prefix frontend dev

