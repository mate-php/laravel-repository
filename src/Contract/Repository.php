<?php

declare(strict_types=1);

namespace Mate\LaravelRepository\Contract;

use Illuminate\Database\Eloquent\Model;

interface Repository
{
    public function all(array $fields = ['*'], array $with = []): mixed;

    public function find(int|string $id, array $fields = ['*'], array $with = []): ?object;

    public function findBy(string $field, mixed $value, array $fields = ['*'], array $with = []): ?object;

    public function findAllBy(string $field, mixed $value, array $columns = ['*'], array $with = []): mixed;

    public function create(array $fields): mixed;

    public function update(int|string|Model $id, array $fields): mixed;

    public function delete(int|string|array|Model $id): void;
}
