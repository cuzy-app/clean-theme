Installation
============

## Allow Google Fonts in the custom CSP Configuration 

If you plan to use Google Fonts, you need to authorize HumHub to download Google Fonts by adding the `https://fonts.gstatic.com` URL in the CSP Settings of the `web.php` configuration file.

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

If you want to build a chid-theme over the Clean theme, see [Documentation here](https://docs.humhub.org/docs/theme/overview) and [Wiki here](https://community.humhub.com/s/theming-appearance/wiki/52/Theme+creation).

The CSS variables generated from the theme configuration (Administration -> Modules -> Clean Theme -> Configure) are served at `<your HumHub URL>/clean-theme-config.css`: use them in your child theme. They are loaded after the theme CSS, so a child theme overwriting one of them in SCSS must use a more specific selector than `:root`.
