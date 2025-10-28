<?php

declare(strict_types=1);

use HamroCDN\Exceptions\HamroCDNException;
use HamroCDN\HamroCDN;
use HamroCDN\Models\Upload;
use HamroCDN\Models\User;

expect()->extend('toBeUploadObject', function () {
    /** @var Upload $upload */
    $upload = $this->value;

    expect($upload)
        ->toBeInstanceOf(Upload::class);

    expect($upload)
        ->toHaveKey('nanoId')
        ->toHaveKey('user')
        ->toHaveKey('delete_at')
        ->toHaveKey('original');

    expect($upload->getOriginal())
        ->toHaveKey('url')
        ->toHaveKey('size');

    expect($upload->getUser())
        ->toBeInstanceOf(User::class);
});

it('checks the dummy data to have correct value in model', function () {
    $dummyData = [
        'nanoId' => 'abc123',
        'user' => [
            'name' => 'John Doe',
            'email' => 'john@hamrocdn.com',
        ],
        'delete_at' => null,
        'original' => [
            'url' => 'https://hamrocdn.com/abc123.png',
            'size' => 2048,
        ],
    ];

    $upload = Upload::fromArray($dummyData);

    expect($upload)->toBeUploadObject();

    expect($upload->getNanoId())->toBe('abc123');
    expect($upload->getDeleteAt())->toBeNull();
    expect($upload->getOriginal()->getUrl())->toBe('https://hamrocdn.com/abc123.png');
    expect($upload->getOriginal()->getSize())->toBe(2048);
    expect($upload->getUser()?->getName())->toBe('John Doe');
    expect($upload->getUser()?->getEmail())->toBe('john@hamrocdn.com');
});

it('returns an array of HamroCDN objects from index', function () {
    $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api');

    $uploads = $client->index();

    expect($uploads)->toBeArray();
    expect($uploads)
        ->toHaveKey('data')
        ->toHaveKey('meta');

    expect($uploads['meta'])
        ->toHaveKey('total')
        ->toHaveKey('per_page')
        ->toHaveKey('page');

    foreach ($uploads['data'] as $upload) {
        expect($upload)->toBeUploadObject();
    }
});

it('uploads a file and returns a HamroCDN object', function () {
    $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api');

    $filePath = __DIR__.'/test.png';
    $upload = $client->upload($filePath);

    expect($upload)->toBeUploadObject();

    $fetchedUpload = $client->fetch($upload->getNanoId());

    expect($fetchedUpload)
        ->toBeUploadObject();

    expect($fetchedUpload->getNanoId())->toBe($upload->getNanoId());
});

it('uploads a file by URL and returns a HamroCDN object', function () {
    $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api');

    $fileUrl = 'https://placehold.co/1000x1000/000000/FFFFFF?text=HamroCDN';

    $upload = $client->uploadByURL($fileUrl);
    expect($upload)->toBeUploadObject();

    $fetchedUpload = $client->fetch($upload->getNanoId());
    expect($fetchedUpload)
        ->toBeUploadObject();
});

describe('exception', function () {
    it('throws exception when API key is missing', function () {
        $this->expectException(HamroCDNException::class);
        $client = new HamroCDN();
        $client->index();
    });

    it('throws exception when uploading a non-existing file', function () {
        $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api');

        $filePath = __DIR__.'/non-existing-file.png';

        $this->expectException(HamroCDNException::class);
        $client->upload($filePath);
    });

    it('throws exception when returns invalid json (GET)', function () {
        $mockHandler = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response(200, [], 'Invalid JSON'),
        ]);
        $handlerStack = GuzzleHttp\HandlerStack::create($mockHandler);
        $guzzleClient = new GuzzleHttp\Client(['handler' => $handlerStack]);

        $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api', $guzzleClient);

        $this->expectException(HamroCDNException::class);
        $client->index();
    });

    it('throws exception when returns invalid json (POST)', function () {
        $mockHandler = new GuzzleHttp\Handler\MockHandler([
            new GuzzleHttp\Psr7\Response(200, [], 'Invalid JSON'),
        ]);
        $handlerStack = GuzzleHttp\HandlerStack::create($mockHandler);
        $guzzleClient = new GuzzleHttp\Client(['handler' => $handlerStack]);

        $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/api', $guzzleClient);

        $filePath = __DIR__.'/test.png';

        $this->expectException(HamroCDNException::class);
        $client->upload($filePath);
    });

    it('throws network error when Guzzle cannot connect to server. (GET)', function () {
        $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/invalid-api');

        $this->expectException(HamroCDNException::class);
        $client->index();
    });

    it('throws network error when Guzzle cannot connect to server. (POST)', function () {
        $client = new HamroCDN('test-api-key', 'https://hamrocdn.com/invalid-api');

        $filePath = __DIR__.'/test.png';

        $this->expectException(HamroCDNException::class);
        $client->upload($filePath);
    });
});
