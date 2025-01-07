# Developer

## Update libraries

```shell
composer update
```

## Compile the CSS

1. Compile the CSS using the "(Re)build Theme CSS" button in Administration -> Settings -> Appearance.
2. Save the module configuration to generate the `resources/css/humhub.clean-theme.dynamic.css` file.

## Upload to the Marketplace

Reset module configuration to default.

The ZIP file mustn't contain:
- .gitignore
- .git
- .github
- composer.lock
- composer.phar
