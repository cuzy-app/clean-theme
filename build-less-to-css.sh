#!/bin/bash

# Compile CSS

# 1) clean-base (dark)
cd themes/clean-base
lessc less/build.less css/theme.css --clean-css="--s1 --advanced" --source-map=css/theme.css.map
# Compile dark CSS for the Dark mode module: https://github.com/felixhahnweilheim/humhub-dark-mode
lessc less/dark/build.less css/dark.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark.css css/dark.css --format=css
# Re-add CSS variables and compress CSS
cp css/dark.css css/temporary.less
lessc less/dark/build2.less css/dark.css --clean-css="--s1 --advanced"
rm css/temporary.less

# 2) clean-bordered (dark)
cd ../clean-contrasted
lessc less/build.less css/theme.css --clean-css="--s1 --advanced" --source-map=css/theme.css.map
# Compile dark CSS for the Dark mode module: https://github.com/felixhahnweilheim/humhub-dark-mode
lessc less/dark/build.less css/dark.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark.css css/dark.css --format=css
# Re-add CSS variables and compress CSS
cp css/dark.css css/temporary.less
lessc less/dark/build2.less css/dark.css --clean-css="--s1 --advanced"
rm css/temporary.less

# 3) clean-contrasted (dark)
cd ../clean-bordered
lessc less/build.less css/theme.css --clean-css="--s1 --advanced" --source-map=css/theme.css.map
# Compile dark CSS for the Dark mode module: https://github.com/felixhahnweilheim/humhub-dark-mode
lessc less/dark/build.less css/dark.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark.css css/dark.css --format=css
# Re-add CSS variables and compress CSS
cp css/dark.css css/temporary.less
lessc less/dark/build2.less css/dark.css --clean-css="--s1 --advanced"
rm css/temporary.less

cd ../..