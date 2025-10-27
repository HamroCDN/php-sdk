<?php

declare(strict_types=1);

use HamroCDN\HamroCDN;

it('returns an array of HamroCDN objects from index', function () {
    $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api');

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
    $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api');

    $filePath = __DIR__.'/test.png';
    $upload = $client->upload($filePath);
    $data = $upload['data'];

    expect($data)
        ->toHaveKey('nanoId')
        ->toHaveKey('user')
        ->toHaveKey('delete_at')
        ->toHaveKey('original');

    $fetchResponse = $client->fetch($data['nanoId']);
    $fetchedData = $fetchResponse['data'];

    expect($fetchedData)
        ->toHaveKey('nanoId')
        ->toHaveKey('user')
        ->toHaveKey('delete_at')
        ->toHaveKey('original');

    expect($fetchedData['nanoId'])->toBe($data['nanoId']);
});

it('uploads a file by URL and returns a HamroCDN object', function () {
    $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api');

    $fileUrl = 'https://placehold.co/1000x1000/000000/FFFFFF?text=HamroCDN';

    $upload = $client->uploadByURL($fileUrl);
    $data = $upload['data'];

    expect($data)
        ->toHaveKey('nanoId')
        ->toHaveKey('user')
        ->toHaveKey('delete_at')
        ->toHaveKey('original');

    $fetchResponse = $client->fetch($data['nanoId']);
    $fetchedData = $fetchResponse['data'];

    expect($fetchedData)
        ->toHaveKey('nanoId')
        ->toHaveKey('user')
        ->toHaveKey('delete_at')
        ->toHaveKey('original');

    expect($fetchedData['nanoId'])->toBe($data['nanoId']);
});

describe('exception', function () {
    it('throws exception when API key is missing', function () {
        $this->expectException(RuntimeException::class);
        new HamroCDN();
    });

    it('throws exception when uploading a non-existing file', function () {
        $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api');

        $filePath = __DIR__.'/non-existing-file.png';

        $this->expectException(RuntimeException::class);
        $client->upload($filePath);
    });

    it('throws exception when returns invalid json', function () {
        $mockHandler = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response(200, [], 'Invalid JSON'),
        ]);
        $handlerStack = GuzzleHttp\HandlerStack::create($mockHandler);
        $guzzleClient = new GuzzleHttp\Client(['handler' => $handlerStack]);

        $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api', $guzzleClient);

        $this->expectException(RuntimeException::class);
        $client->index();
    });
});
