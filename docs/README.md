# Clean Theme

"Clean" theme based on the community "HumHub" theme.

## Overview

- Modern, smooth and uncluttered theme
- Module configuration to customize colors, fonts, borders sizes and to add custom SCSS code (with upload/download functionality)
- On large screens: the two top menu bars of the default theme are merged
- On small screens:
    - the second menu becomes a bottom menu bar
    - on scrolling down, the top and bottom menus are hidden
- The left menu is sticky when scrolling vertically
- The top right icon becomes active when it's the URL of the current page.
- Adds the profile's header in the user's account pages
- Compatible with the [Dark Mode module](https://marketplace.humhub.com/module/dark-mode/description)
- Possibility to collapse the left navigation menu (in a space, profile, account and admin menu) with the `collapsibleLeftNavigation` property ([see the documentation](https://docs.humhub.org/docs/admin/advanced-configuration)).
- You can create a child theme manually (see instructions below) or using the [Theme Builder module](https://marketplace.humhub.com/module/theme-builder)

## Configuration

1. Administration -> Modules: install and activate the module
2. Administration -> Settings -> Appearance: select the theme
3. Advanced configuration: See available public properties in [the `Module.php` file](https://github.com/cuzy-app/clean-theme/blob/master/Module.php) and [the Module configuration documentation](https://docs.humhub.org/docs/admin/advanced-configuration#module-configurations)

## Pricing

This module is free, but the result of a lot of work for design and maintenance over time.

If it's useful to you, please consider [making a donation](https://www.cuzy.app/checkout/donate/) or [participating in the code](https://github.com/cuzy-app/clean-theme). Thanks!

## Child themes

See [Documentation here](https://docs.humhub.org/docs/theme/overview) and [Wiki here](https://community.humhub.com/s/theming-appearance/wiki/52/Theme+creation).

You can start with [this empty template](https://github.com/cuzy-app/clean-theme/blob/master/docs/Clean-Child.zip), which is a child theme of the `Clean` theme.

Use available CSS variables in `protected/modules/clean-theme/resources/css/humhub.clean-theme.dynamic.css`.

Unzip it in the `/themes` root folder (not in `protected`).

## Repository

https://github.com/cuzy-app/clean-theme

## Publisher

[CUZY.APP](https://www.cuzy.app/)

## Licence

[GNU AGPL](https://github.com/cuzy-app/clean-theme/blob/master/docs/LICENCE.md)
