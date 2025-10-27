<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;

try {
    return RectorConfig::configure()
        ->withPaths([
            __DIR__.'/src',
        ])
        ->withPreparedSets(
            deadCode: true,
            codeQuality: true,
            typeDeclarations: true,
            privatization: true,
            earlyReturn: true,
        )
        ->withSkip([
            RemoveNonExistingVarAnnotationRector::class,
        ])
        ->withPhpSets(
            php84: true,
        );
} catch (Rector\Exception\Configuration\InvalidConfigurationException $e) {
    echo 'Rector configuration error: '.$e->getMessage().PHP_EOL;
    exit(1);
}
