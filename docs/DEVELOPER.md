# Developer

### Build CSS

CSS must be rebuilt for each new HumHub version:
1. Install [less](https://lesscss.org/usage/#command-line-usage-installing) and [css-color-extractor](https://github.com/rsanchez/css-color-extractor#cli).
2. Install [Dark mode module](https://marketplace.humhub.com/module/dark-mode/description) in the same "Module" directory.
3. Execute `build-less-to-css.sh` script to
    - create static files with CSS variables (replaces darken, lighten and fade LESS functions with CSS variables)
    - compile LESS to CSS
