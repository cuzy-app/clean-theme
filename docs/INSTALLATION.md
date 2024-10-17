Installation
============

## Custom CSP Configuration 

If you plan to use Google Fonts, you need to add the following configuration to the `protected/config/web.php` file: 

```php
return [
    'modules' => [
        'web' => [
            'security' => [
                "headers" => [
                    "Content-Security-Policy" => "default-src *; connect-src  *; font-src 'self' https://fonts.gstatic.com; frame-src https://* http://* *; img-src https://* http://* * data:; object-src 'self'; script-src {{ nonce }} 'self' https://* http://* * 'unsafe-inline' 'report-sample'; style-src * https://* http://* * 'unsafe-inline';",
                ],
            ],
        ],
    ],
];
``` 

## Child themes

If you want to build a chid-theme over the Clean theme, see [Documentation here](https://docs.humhub.org/docs/theme/overview) and [Wiki here](https://community.humhub.com/s/theming-appearance/wiki/52/Theme+creation).

You can start with [this empty template](https://github.com/cuzy-app/clean-theme/blob/master/docs/Clean-Child.zip), which is a child theme of the `Clean` theme.

Use available CSS variables in `protected/modules/clean-theme/resources/css/humhub.clean-theme.dynamic.css`.

Unzip it in the `/themes` root folder (not in `protected`).
