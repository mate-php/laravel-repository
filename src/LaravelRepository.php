<?php

declare(strict_types=1);

namespace Mate\LaravelRepository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Mate\LaravelRepository\Contract\Repository;

abstract class LaravelRepository implements Repository
{
    protected string $model = '';

    public function __construct()
    {
    }

    public function all(array $fields = ['*'], array $with = []): Collection
    {
        $model = $this->model;
        return $model::select($fields)
            ->with($with)
            ->get();
    }

    public function find(int|string $id, array $fields = ['*'], array $with = []): ?Model
    {
        $model = $this->model;

        return $model::select($fields)
            ->with($with)
            ->where('id', $id)
            ->first();
    }

    public function findBy(string $field, mixed $value, array $fields = ['*'], array $with = []): ?Model
    {
        return $this->model::select($fields)
            ->with($with)
            ->where($field, $value)
            ->first();
    }

    public function findAllBy(string $field, mixed $value, array $fields = ['*'], array $with = []): Collection
    {
        return $this->model::select($fields)
            ->with($with)
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

    public function delete(int|string|array|Model $id): void
    {
        if (is_object($id)) {
            $id->delete();
            return;
        }

        if (is_array($id)) {
            $this->model::whereIn('id', $id)
                ->delete();
        } else {
            $this->model::where('id', $id)
                ->delete();
        }

        return;
    }
}
