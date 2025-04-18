#!/bin/bash
# shellcheck disable=SC2164

# Create files in resources/less/humhub.modified with special colors
php ../../yii clean-theme/build-modified-sources

# Compile CSS
cd themes/Clean
lessc less/build.less css/theme.css --clean-css --source-map=css/theme.css.map && rm -rf ../../resources/less/humhub.modified

echo "CSS compiled. Save module configuration to generated humhub.clean-theme.dynamic.css file"
