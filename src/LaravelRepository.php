<?php

declare(strict_types=1);

namespace Mate\LaravelRepository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Mate\Repository\Repository;

abstract class LaravelRepository extends Repository
{
    protected string $model = '';

    public function __construct()
    {
    }

    public function all(array $fields = ['*']): Collection
    {
        $model = $this->model;
        return $model::select($fields)
            ->get();
    }

    public function find(int|string $id, array $fields = ['*']): ?Model
    {
        $model = $this->model;

        return $model::select($fields)
            ->where('id', $id)
            ->first();
    }

    public function findBy(string $field, mixed $value, array $fields = ['*']): ?Model
    {
        return $this->model::select($fields)
            ->where($field, $value)
            ->first();
    }

    public function findAllBy(string $field, mixed $value, array $fields = ['*']): Collection
    {
        return $this->model::select($fields)
            ->where($field, $value)
            ->get();
    }

    public function create(array $fields): Model
    {
        return $this->model::create($fields);
    }

    public function update(int|string|Model $id, array $fields): int|Model
    {
        if (is_object($id)) {
            $id->fill($fields);
            $id->save();
            return $id;
        }

        return $this->model::where('id', $id)
            ->update($fields);
    }

    public function delete(int|string|Model $id): void
    {
        if (is_object($id)) {
            $id->delete();
            return;
        }

        $this->model::where('id', $id)
            ->delete();

        return;
    }
}
