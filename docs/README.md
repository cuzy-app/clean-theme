# Clean Theme

Clean theme for Humhub based on the Community theme


## Overview

- Modern, smooth and uncluttered theme
- Variants : Contrasted and Bordered
- Merges the 2 top menu bars of the community theme into 1 menu bar
- Adds the profile's header in the user's account pages
- You can create a child theme to customize colors, fonts, etc. (see instructions bellow) or use the [Theme Builder module](https://www.humhub.com/en/marketplace/theme-builder/) (works only on the `clean-base` theme).


## Configuration

1. Administration -> Modules: install and activate the module
2. Administration -> Settings -> Appearance: select the theme


## Pricing

This module is free, but is the result of a lot of work for the design and maintenance over time.

If it's useful to you, please consider [making a donation](https://www.cuzy.app/checkout/donate/) or [participating in the code](https://github.com/cuzy-app/humhub-modules-clean-theme). Thanks!

## Child themes

To customize colors, fonts, etc., create a child theme (don't forget to copy img and ico folders).
[See documentation here](https://docs.humhub.org/docs/theme/overview).

Import the less files in `less/build.less` (see example in `themes/clean-contrasted`) and the specify the parent theme [as explained here](https://docs.humhub.org/docs/theme/css) defining the variable `@baseTheme: "clean-base";` in the `less/variables.less` (1).

And then overwrite the variable values using [the variables names mentioned here](https://github.com/humhub/humhub/blob/master/static/less/variables.less).
You can load new fonts by [downloading the via this Google webfonts helper site](https://google-webfonts-helper.herokuapp.com/fonts).

(1) This will allow the child theme to use view pages of clean-base (you also can use a sub-theme such as clean-contrasted as a parent theme). If you change the parent theme, flushing cache has no effect, you need to switch to another theme and switch back to your child theme.


## Repository

https://github.com/cuzy-app/humhub-modules-clean-theme


## Publisher

[CUZY.APP](https://www.cuzy.app/)


## Licence

[GNU AGPL](https://github.com/cuzy-app/humhub-modules-clean-theme/blob/master/docs/LICENCE.md)