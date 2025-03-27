<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Mate\LaravelRepository\LaravelRepository;

class SiteLaravelRepository extends LaravelRepository
{
    protected string $table = 'sites';
    protected string $model = SiteLaravelModel::class;
}
