<?php

/**
 * Shared config for all test suites.
 * The module is not listed in `modules`: it is enabled in the test database, and enabling it for each test would
 * activate its theme.
 */
return [
    'fixtures' => [
        'default',
    ],
];
