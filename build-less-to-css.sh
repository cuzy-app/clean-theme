#!/bin/bash

# Compile CSS

# 1) clean-base
cd themes/clean-base
lessc less/build.less css/theme.css --clean-css="--s1 --advanced" --source-map=css/theme.css.map
# Compile dark CSS for the Dark mode module: https://github.com/felixhahnweilheim/humhub-dark-mode
lessc less/dark/build-for-colors.less css/dark-for-colors.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark-for-colors.css css/dark-colors.css --format=css
# Re-add CSS variables and compress CSS
mv css/dark-colors.css css/dark-colors.less
lessc less/dark/build-final.less css/dark.css --clean-css="--s1 --advanced"
rm css/dark-for-colors.css
rm css/dark-colors.less

# 2) clean-bordered
cd ../clean-contrasted
lessc less/build.less css/theme.css --clean-css="--s1 --advanced" --source-map=css/theme.css.map
# Compile dark CSS for the Dark mode module: https://github.com/felixhahnweilheim/humhub-dark-mode
lessc less/dark/build-for-colors.less css/dark-for-colors.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark-for-colors.css css/dark-colors.css --format=css
# Re-add CSS variables and compress CSS
mv css/dark-colors.css css/dark-colors.less
lessc less/dark/build-final.less css/dark.css --clean-css="--s1 --advanced"
rm css/dark-for-colors.css
rm css/dark-colors.less

# 3) clean-contrasted
cd ../clean-bordered
lessc less/build.less css/theme.css --clean-css="--s1 --advanced" --source-map=css/theme.css.map
# Compile dark CSS for the Dark mode module: https://github.com/felixhahnweilheim/humhub-dark-mode
lessc less/dark/build-for-colors.less css/dark-for-colors.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark-for-colors.css css/dark-colors.css --format=css
# Re-add CSS variables and compress CSS
mv css/dark-colors.css css/dark-colors.less
lessc less/dark/build-final.less css/dark.css --clean-css="--s1 --advanced"
rm css/dark-for-colors.css
rm css/dark-colors.less

cd ../..
