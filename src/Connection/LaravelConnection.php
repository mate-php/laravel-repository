<?php

declare(strict_types=1);

namespace Mate\LaravelRepository\Connection;

use Illuminate\Database\Capsule\Manager as Capsule;

class LaravelConnection
{
    public static function create(array $options): Capsule
    {
        $conn = new Capsule();
        $conn->addConnection($options);
        $conn->setAsGlobal();
        $conn->bootEloquent();

        return $conn;
    }
}
