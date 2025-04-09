#!/bin/bash
set -x

# get prod variables
(
reg='(.+): *(.+)'

# get version
line=$(sed -n '/^app_version: /p' vars.yml)
[[ "$line" =~ $reg ]]
version="${BASH_REMATCH[2]}"

# get backend host
line=$(sed -n '/^backend_server_name: /p' vars.yml)
[[ "$line" =~ $reg ]]
backend_host="${BASH_REMATCH[2]}"

# build front
cd ../../frontend &&
  echo "VITE_VERSION=$version" > .env.production.local &&
  echo "VITE_BACKEND_BASE_URL=https://$backend_host" >> .env.production.local &&
  npm run build-only
)
