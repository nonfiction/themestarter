<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rootFiles = array_filter([
    __DIR__ . '/functions.php',
    __DIR__ . '/index.php',
    __DIR__ . '/header.php',
    __DIR__ . '/footer.php',
    __DIR__ . '/sidebar.php',
], 'file_exists');

$finder = (new Finder())
    ->files()
    ->name('*.php')
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/src',
    ])
    ->append($rootFiles)
    ->exclude([
        'assets/dist',
        'build',
        'coverage',
        'dist',
        'node_modules',
        'vendor',
    ]);

return (new Config())
    ->setIndent('  ')
    ->setRiskyAllowed(false)
    ->setUnsupportedPhpVersionAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'single_space'],
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'return',
                'throw',
                'try',
            ],
        ],
        'concat_space' => ['spacing' => 'one'],
        'no_extra_blank_lines' => true,
        'no_unused_imports' => true,
        'ordered_imports' => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],
        'single_quote' => true,
        'trailing_comma_in_multiline' => [
            'elements' => [
                'arguments',
                'arrays',
                'parameters',
            ],
        ],
        'yoda_style' => false,
    ])
    ->setFinder($finder);
