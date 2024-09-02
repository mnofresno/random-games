#!/bin/bash

set -eu

cd $(dirname $0)

docker run \
    --rm -v $(pwd)/../:/app \
    -v $(pwd):/etc/linter \
    ghcr.io/mnofresno/php_linter:0.0.2 \
    php-cs-fixer fix /app \
        --config=/etc/linter/php_cs_custom_rules.php \
        --using-cache=no \
        --diff \
        --verbose $@
