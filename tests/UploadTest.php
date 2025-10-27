<?php

declare(strict_types=1);

use HamroCDN\HamroCDN;
it('returns an array of HamroCDN objects from index', function () {
    $client = new HamroCDN('test-api-key', 'https://hamrocdn.test/api');

    $uploads = $client->index();

    expect($uploads)->toBeArray();
    expect($uploads)
        ->toHaveKey('data')
        ->toHaveKey('meta');

    foreach ($uploads['data'] as $upload) {
        expect($upload)
            ->toHaveKey('nanoId')
            ->toHaveKey('user')
            ->toHaveKey('delete_at')
            ->toHaveKey('original');

        expect($upload['original'])
            ->toHaveKey('url')
            ->toHaveKey('size');
    }

    expect($uploads['meta'])
        ->toHaveKey('total')
        ->toHaveKey('per_page')
        ->toHaveKey('page');
});
