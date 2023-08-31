#!/bin/bash

# Compile CSS

cd themes/clean-base/less
lessc ./build.less ../css/theme.css --clean-css="--s1 --advanced" --source-map=../css/theme.css.map
cd ../../clean-contrasted/less
lessc ./build.less ../css/theme.css --clean-css="--s1 --advanced" --source-map=../css/theme.css.map
cd ../../clean-bordered/less
lessc ./build.less ../css/theme.css --clean-css="--s1 --advanced" --source-map=../css/theme.css.map
cd ../../..

# Compile dark CSS for the Dark mode module: https://github.com/felixhahnweilheim/humhub-dark-mode

# 1) clean-base (dark)
cd themes/clean-base
lessc less/dark/build.less css/dark.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark.css css/dark.css --format=css
# Re-add CSS variables and compress CSS
cp css/dark.css css/temporary.less
lessc less/dark/build2.less css/dark.css --clean-css="--s1 --advanced" --source-map=css/dark.css.map
rm css/temporary.less
cd ../../

# 2) clean-bordered (dark)
cd themes/clean-bordered
lessc less/dark/build.less css/dark.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark.css css/dark.css --format=css
# Re-add CSS variables and compress CSS
cp css/dark.css css/temporary.less
lessc less/dark/build2.less css/dark.css --clean-css="--s1 --advanced" --source-map=css/dark.css.map
rm css/temporary.less
cd ../../

# 3) clean-contrasted (dark)
cd themes/clean-contrasted
lessc less/dark/build.less css/dark.css
# Note: Unfortunately the color extractor removes the CSS variables
css-color-extractor css/dark.css css/dark.css --format=css
# Re-add CSS variables and compress CSS
cp css/dark.css css/temporary.less
lessc less/dark/build2.less css/dark.css --clean-css="--s1 --advanced" --source-map=css/dark.css.map
rm css/temporary.less
cd ../../
