<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Mate\LaravelRepository\Connection\LaravelConnection;

describe('Connection', function () {
    test('Laravel', function () {
        $options = [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ];

        $conn = LaravelConnection::create($options);

        expect($conn)->toBeInstanceOf(Capsule::class);
    });
});
