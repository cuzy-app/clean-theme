# Developer

## Update libraries

```shell
composer update
```

## Create modified HumHub LESS files and build CSS

CSS must be rebuilt for each new HumHub version:
1. Install [less](https://lesscss.org/usage/#command-line-usage-installing) and [css-color-extractor](https://github.com/rsanchez/css-color-extractor#cli).
2. Execute `build-modified-less-and-css` script (to create the files in `resources/less`)
3. Save the module configuration to generate the `resources/css/humhub.clean-theme.dynamic.css` file.

## Upload to the Marketplace

Reset module configuration to default.

The ZIP file mustn't contain:
- .gitignore
- .git
- .github
- composer.lock
- composer.phar
- build-modified-less-and-css.sh
