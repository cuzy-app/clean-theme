# Developer

## Update libraries

```shell
composer update
```

## Create modified HumHub LESS files and build CSS

CSS must be rebuilt for each new HumHub version:
1. Install [less](https://lesscss.org/usage/#command-line-usage-installing) and [css-color-extractor](https://github.com/rsanchez/css-color-extractor#cli).
2. Install [Dark mode module](https://marketplace.humhub.com/module/dark-mode/description) in the same "Module" directory.
3. Execute `build-modified-less-and-css` script

This will create the files in `resources/less`. Then, save the module configuration to generate the `resources/css/humhub.clean-theme.dynamic.css` file.

## Upload to the Marketplace

The ZIP file mustn't contain:
- build-modified-less-and-css.sh
- .gitignore
- composer.lock
- composer.phar
- resources/less/humhub.modified
- resources/css/humhub.clean-theme.dynamic.css
