<?php

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixer\{Config, Finder};

$finder = (new Finder())
    ->ignoreDotFiles(true)
    ->ignoreVCSIgnored(true)
    ->exclude(['tests/'])
    ->in(__DIR__);

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        '@PSR12' => true,
        'ordered_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'single_import_per_statement' => false,
        'single_blank_line_at_eof' => true,
        'blank_lines_before_namespace' => true,
        'single_line_after_imports'    => true,
        'no_unused_imports'            => true,
        'group_import'                 => true,
        'assign_null_coalescing_to_coalesce_equal' => true,
        'global_namespace_import'      => [
            'import_classes'   => true,
            'import_functions' => true,
        ],
    ])
    ->setFinder($finder);
