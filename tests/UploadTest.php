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

it('uploads a file and returns a HamroCDN object', function () {
    $client = new HamroCDN('test-api-key', 'https://hamrocdn.test/api');

    $filePath = __DIR__.'/test.png';
    $upload = $client->upload($filePath);
    $data = $upload['data'];

    expect($data)
        ->toHaveKey('nanoId')
        ->toHaveKey('user')
        ->toHaveKey('delete_at')
        ->toHaveKey('original');

    $fetchResponse = $client->fetch($data['nanoId']);
    var_dump($fetchResponse);
    $fetchedData = $fetchResponse['data'];

    expect($fetchedData)
        ->toHaveKey('nanoId')
        ->toHaveKey('user')
        ->toHaveKey('delete_at')
        ->toHaveKey('original');

    expect($fetchedData['nanoId'])->toBe($data['nanoId']);
});
