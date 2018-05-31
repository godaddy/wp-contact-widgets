#!/bin/bash

sudo pkill chrome
sudo pkill chromedriver

if [ "${TRAVIS}" = true ]; then
    bin/chromedriver-linux --url-base=/wd/hub &
    vendor/bin/codecept run -vvv --fail-fast --steps ${@:6:99}
else
    bin/chromedriver-mac --url-base=/wd/hub &
    vendor/bin/codecept run -vvv --fail-fast --steps ${@:6:99}
fi
