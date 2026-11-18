Installation
============

## Allow Google Fonts in the custom CSP Configuration (only if the server cannot reach Google Fonts)

When the theme configuration is saved (and when the module is updated), the Google Fonts are downloaded to the server and served from it, so browsers don't contact Google. If the server cannot reach Google Fonts (e.g. no outgoing connection), a warning is shown when saving the configuration and the fonts are loaded from Google by the browsers instead.

Only in this case, you need to authorize HumHub to download Google Fonts by adding the `https://fonts.gstatic.com` URL in the CSP Settings of the `web.php` configuration file.

Example of configuration for the `protected/config/web.php` file: 

```php
return [
    'modules' => [
        'web' => [
            'security' =>  [
                "csp" => [
                    "font-src" => [
                        "self" => true,
                        "allow" => [
                            "https://fonts.gstatic.com",
                        ],
                    ],
                ],
            ],
        ],
    ],
];
``` 

More information about the CSP configuration can be found in the [HumHub documentation](https://docs.humhub.org/docs/admin/security#strict-csp-settings).

## Child themes

If you want to build a child theme over the Clean theme, see [Documentation here](https://docs.humhub.org/docs/theme/overview) and [Wiki here](https://community.humhub.com/s/theming-appearance/wiki/52/Theme+creation).

The CSS variables generated from the theme configuration (Administration -> Modules -> Clean Theme -> Configure) are served at `<your HumHub URL>/clean-theme-config-css`: use them in your child theme. They are loaded after the theme CSS, so a child theme overwriting one of them in SCSS must use a more specific selector than `:root`.
