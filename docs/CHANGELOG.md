Changelog
=========

2.0-beta.1 (unreleased - !! the current branch is version-2 !! - Release in beta for a start)
--------------------
TODO:
- Screenshots
- Find a solution not to upload a custom `humhub.clean-theme.dynamic.css` when uploading the module to the marketplace
- Replace `protected/modules/clean-theme/resources/css/humhub.clean-theme.dynamic.css` (including README) with the GitHub URL
- Comment on https://github.com/humhub/humhub/issues/7099 to explain what was done with this Version 2

Important notice: this version 2 merges the 3 themes to a unique "Clean" theme. Please check the new module configuration. If you have created a custom child theme, update it to the new `Clean` parent theme and replace the colors to the new CSS variables available in `protected/modules/clean-theme/resources/css/humhub.clean-theme.dynamic.css`

- Enh: Add module configuration to define colors, fonts, borders and other CSS parameters of the theme (Thanks to @felixhahnweilheim for the code generating CSS variable color variations from his [Flex Theme](https://github.com/felixhahnweilheim/humhub-flex-theme))
- Chg: Remove `clean-base`, `clean-contrasted` and `clean-bordered` themes and replace them with the new `Clean` theme
- Chg: CSS compiled for HumHub 1.16.1

1.8.1 (June 19, 2024)
--------------------
- Enh: Remove the top arrow of the new search dropdown modal box
- Chg: CSS compiled for HumHub 1.16.0

1.8.0 (May 11, 2024)
--------------------
- Chg: CSS compiled for HumHub 1.16-beta.2
- Enh: Make the view files work even if the module is disabled

1.7.5 (April 6, 2024)
--------------------
- Fix #17: Dark Mode for Clean Base theme

1.7.4 (April 5, 2024)
--------------------
- Fix #11: Search results when filtering in the space browser
- Fix #17: Dark Mode: unread notification background
- Chg: Repository URL from https://github.com/cuzy-app/humhub-modules-clean-theme to https://github.com/cuzy-app/clean-theme

1.7.3 (March 27, 2024)
--------------------
- Fix #16: On mobile, top menu dropdown notification panels are not centered on the screen and the logo is hidden

1.7.2 (March 21, 2024)
--------------------
- Enh #11: Add the module configuration `hideTextInBottomMenuItems` (see https://docs.humhub.org/docs/admin/advanced-configuration#module-configurations)
- Enh: Replace the "Like" text with an icon
- Fix #13: On touch screens, when a menu element is clicked, the background color is the one of the hover background color
- Fix #13: the fix for drop down menus (such as the Space chooser) in vers 1.7.1 doesn't work in some specific situations
- Fix #11: Prevent top left application name to display on multiple lines
- Fix #11: Too much vertical space between menu items in the space browser
- Enh: Change the "Share" link (Share between module) to an icon (requires https://github.com/humhub/sharebetween/pull/58)
- Enh: Better vertical alignement of wall entry bottom links
- Enh: When a content is liked, the "Like" icon is plain
- Fix #11: Space Browser drop up button in bottom navigation menu (mobile screen)
- Enh: Transition duration when hiding menus (on mobile scroll down) changed from 0.3 to 0.6 seconds
- Fix #14: small dark mode issues (thanks @felixhahnweilheim)

1.7.1 (March 11, 2024)
--------------------
- Fix #12: On iPhone X and above, the bottom menu is too low and interferes with unclickable interactive elements
- Fix: Drop down menus (such as the Space chooser) must become drop up menus when the main navigation bar becomes a bottom bar on small screens

1.7.0 (March 7, 2024)
--------------------
- Enh: On small screens, the main menu is divided in 2: top and bottom menu
- Enh: On small screens and on scrolling down, the top and bottom menus are hidden (this feature can be disabled in the configuration file)
- Fix: The top menu "Account" item (at the right) needs a right margin on mobile screens
- Fix: Space of User banner image stretched (change to cover)
- Enh: On the top menu, the right icons should have a grey background on hover, and the primary color when active
- Enh: Replace the "Comment" text with an icon
- Enh: Move the "Search" menu item at the right, next to the "Bell" icon, and remove the "Search" label, to have a smaller icon which is better for the mobile view
- Enh: Make the left menu fixed when scrolling down (if the screen hight is sufficient)
- Enh: Top menu icon buttons: update active status
- Chg: On the space header, add a linear gradient at the top of the black translucent layer, and a text shadow on the space name and description
- Fix: If the "clean-bordered" or "clean-contrasted" themes are active, after disabling the module, the theme is not switched to the HumHub one
- Enh: Updated screenshots

1.6.0 (Nov 23, 2023)
--------------------
- Enh: Make the main container larger (reduce left and right margin)
- Enh: Remove left and right spaces on the top bar
- Enh: Remove border radius on module logos
- Enh: Make space logos round even if no logo image is loaded
- Chg: Change the module logo
- Enh: Panel, container header and modal box corners should be made consistent, 4px rounded
- Enh: "Add Search label to top menu Search entry" feature should be done in JS instead of overwriting a view
- Fix: Space header subtitles get cutoff for some letters such `g`, `p` or `q`
- Enh: CSS compiled for [Dark Module version 1.0.1](https://github.com/felixhahnweilheim/humhub-dark-mode/releases/tag/v1.0.1)

1.5.2 (Nov 10, 2023)
--------------------
- Chg: CSS compiled for HumHub 1.15.0
- Fix #8: Dark Themes: remove css maps for the dark stylesheets, according to https://github.com/felixhahnweilheim/humhub-dark-mode/pull/16 and CSS recompiled with fix https://github.com/felixhahnweilheim/humhub-dark-mode/pull/17 (thanks @felixhahnweilheim)

1.5.1 (Sept 9, 2023)
--------------------
- Fix #6: Mobile: top bar issues with added items in the notifications block such as the night mode button
- Enh #7: Dark CSS for the [Dark mode](https://marketplace.humhub.com/module/dark-mode/description) module (thanks @felixhahnweilheim)
- Chg: CSS compiled for HumHub 1.15.0-beta.2 (changed online indicator position in the people directory)
- Fix: Position of the online status indicator because of rounded profile image on this theme

1.5.0 (August 1, 2023)
--------------------
- Chg: CSS compiled for HumHub 1.15
- Chg: The minimal HumHub version is now 1.15
- Chg: Colors on the Contrasted theme are more contrasted to meet visually impaired requirements (tested on https://www.whocanuse.com/)

1.4.2 (May 24, 2023)
--------------------
- Chg: On large screens, container max width is now 1600 px (instead of bootstrap native 1170px)
- Enh #3: On profile or space header, if a banner image is present, add a translucent black background layer to better see the text (thanks @dantefromhell)
- Chg: On clean theme "base" and "bordered", on profile or space header, the text is now white and if no image is present, the background is with the primary color (it was already the case with the "contrasted" theme)

1.4.1 (May 4, 2023)
--------------------
- Chg: Moved `@smallScreen` and `@tinyScreen` from `humhub+mobile.less` to `variables.less`
- Chg: Replaced `@headings-color` with `@text-color-main`
- Enh: Added config page (for admins)
- Enh: Added a child theme example (see [docs/README.md](https://github.com/cuzy-app/clean-theme/blob/master/docs/README.md#child-themes))

1.4.0 (March 11, 2023)
--------------------
- Chg: CSS compiled for HumHub 1.14
- Chg: Minimal HumHub version is now 1.14
- Fix: Removed the gap between the content and the top menu on mobile view (thanks @Eladnarlea)

1.3.1 (February 10, 2023)
--------------------
- Fix: When the module was disabled, the theme was not changed to the default HumHub theme (thanks @luke-).
- Enh: When the module is enabled, the theme is automatically changed to the clean-base theme (thanks @luke-).

1.3.0 (February 3, 2023)
--------------------
- Enh: Added possibility to collapse the left navigation menu (in a space, profile, account and admin menu) with the `collapsibleLeftNavigation` property ([see the documentation](https://docs.humhub.org/docs/admin/advanced-configuration)).
- Chg: On the top menu, added "Search" text below the magnifying glass icon
- Chg: "Success" and "Warning" colors (green and orange) are now darker to have a better contrast with the white text inside buttons
- Chg: Button font weight is now 600 instead of 500 to read better the text
- Fix: In the account top menu (dropdown menu at the top right), removed left bar when hovering the menu items
- Fix: CSS build for bordered theme

1.2.0 (January 3, 2023)
--------------------
- Chg: CSS compiled for HumHub 1.13
- Chg: Minimal HumHub version is now 1.13

1.1.1 (September 27, 2022)
--------------------
- Fix: On small screens, the top menu items could have text not centered

1.1 (September 25, 2022)
--------------------
- Fix: Position of notifications (+ mail) dropdown on mobile (thanks @felixhahnweilheim)

1.0 (September 20, 2022)
--------------------
- Fix: Small fixes for the menu on small screens
- Enh: Tested enough for releasing version 1.0

0.3 (August 26, 2022)
--------------------
- Fix: Added compatibility with Theme Builder module (for `clean-base` theme)
- Chg: Compiled for HumHub 1.12

0.2 (August 23, 2022)
--------------------
- Enh: Fix menu dropdown when browser is extended (thanks @sebmennetrier)
- Chg: Removed dark theme

0.1 (May 12, 2022)
--------------------
- Enh: Initial commit
