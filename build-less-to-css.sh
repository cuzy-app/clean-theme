#!/bin/bash

# Compile CSS

cd themes/Clean
lessc less/build.less css/theme.css --clean-css="--s1 --advanced" --source-map=css/theme.css.map
# Compile dark CSS for the Dark mode module: https://github.com/felixhahnweilheim/humhub-dark-mode
lessc less/dark/build.less css/dark.css --clean-css="--s1 --advanced"

cd ../..
