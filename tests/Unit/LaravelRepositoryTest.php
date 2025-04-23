<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Mate\LaravelRepository\Connection\LaravelConnection;
use Tests\Helpers\SiteLaravelModel;
use Tests\Helpers\SiteLaravelRepository;

$options = [
    'driver'   => 'sqlite',
    'database' => ':memory:',
    'prefix'   => '',
];

$conn = LaravelConnection::create($options);

beforeAll(function () use ($conn) {
    prepareSqliteInMemory($conn->connection()->getPdo());
    prepareSqliteInMemory($conn->connection()->getPdo());
});


afterAll(function () use ($conn) {
    freeSqliteInMemory($conn->connection()->getPdo());
});

describe('Laravel Repository', function () {
    test('find', function () {
        $repository = new SiteLaravelRepository();
        $result = $repository->find(1);

        expect($result)->toBeInstanceOf(SiteLaravelModel::class);
        expect($result->id)->toBe(1);
    });

    test('find dont', function () {
        $repository = new SiteLaravelRepository();
        $result = $repository->find(9999);

        expect($result)->toBeNull();
    });

    test('find with fields', function () {
        $repository = new SiteLaravelRepository();
        $result = $repository->find(1, ["id", "code"]);

        expect($result)->toBeInstanceOf(SiteLaravelModel::class);
        expect($result->id)->toBe(1);
        expect($result->code)->toBe('code-1');
    });

    test('all', function () {
        $repository = new SiteLaravelRepository();
        $result = $repository->all();

        expect($result)->toBeInstanceOf(Collection::class);
        expect($result)->toHaveCount(2);
    });

    test('findBy', function () {
        $repository = new SiteLaravelRepository();
        $data = $repository->findBy('code', 'code-1');

        expect($data)->toBeInstanceOf(Model::class);
        expect($data->code)->toBe('code-1');
    });

    test('findAllBy', function () {
        $repository = new SiteLaravelRepository();
        $data = $repository->findAllBy('code', 'code-1');

        expect($data)->toBeInstanceOf(Collection::class);
        expect($data[0]->code)->toBe('code-1');
        expect($data)->toHaveCount(1);
    });

    test('findAllBy empty', function () {
        $repository = new SiteLaravelRepository();
        $data = $repository->findAllBy('code', 'invalid');

        expect($data)->toBeInstanceOf(Collection::class);
        expect($data)->toHaveCount(0);
    });

    test('create', function () {
        $repository = new SiteLaravelRepository();
        $fields = ['code' => '123'];

        $model = $repository->create($fields);

        expect($model)->toBeInstanceOf(Model::class);
        expect($model->deleted_at)->toBeNull();
        expect($model->created_at)->toBeInstanceOf(Carbon::class);
        expect($model->updated_at)->toBeInstanceOf(Carbon::class);
    });

    test('createBulk', function () {
        $repository = new SiteLaravelRepository();
        $fields = [
            ['code' => '123'],
            ['code' => 'abc'],
        ];

        $result = $repository->createBulk($fields);

        expect($result)->toBeTrue();
    });

    test('update', function () {
        $repository = new SiteLaravelRepository();
        $fields = ['code' => 'new code laravel'];

        $affected = $repository->update(1, $fields);
        $updatedRow = $repository->find(1);

        expect($affected)->toBe(1);
        expect($updatedRow)->toBeInstanceOf(Model::class);
        expect($updatedRow->id)->toBe(1);
        expect($updatedRow->code)->toBe('new code laravel');
    });

    test('update with model', function () {
        $repository = new SiteLaravelRepository();
        $fields = ['code' => 'new code laravel'];
        $model = SiteLaravelModel::create(['code' => 'code']);

        $updatedModel = $repository->update($model, $fields);

        expect($updatedModel)->toBeInstanceOf(Model::class);
        expect($updatedModel->id)->toBe($model->id);
        expect($updatedModel->code)->toBe('new code laravel');
    });

    test('delete', function () {
        $repository = new SiteLaravelRepository();
        $model = SiteLaravelModel::create(['code' => 'code']);
        $newModel = $repository->find($model->id);

        expect($newModel)->toBeInstanceOf(Model::class);
        expect($newModel->id)->toBe($model->id);

        $repository->delete($newModel->id);

        $deletedModel = $repository->find($model->id);
        expect($deletedModel)->toBeNull();
    });

    test('delete from model', function () {
        $repository = new SiteLaravelRepository();
        $model = SiteLaravelModel::create(['code' => 'code']);
        $newModel = $repository->find($model->id);

        expect($newModel)->toBeInstanceOf(Model::class);
        expect($newModel->id)->toBe($model->id);

        $repository->delete($newModel);

        $deletedModel = $repository->find($model->id);
        expect($deletedModel)->toBeNull();
    });

    test('delete from array', function () {
        $repository = new SiteLaravelRepository();
        $modelA = SiteLaravelModel::create(['code' => 'code']);
        $modelB = SiteLaravelModel::create(['code' => 'abc']);

        $newModelA = $repository->find($modelA->id);
        $newModelB = $repository->find($modelB->id);

        expect($newModelA)->toBeInstanceOf(Model::class);
        expect($newModelA->id)->toBe($modelA->id);
        expect($newModelB)->toBeInstanceOf(Model::class);
        expect($newModelB->id)->toBe($modelB->id);

        $repository->delete([
            $modelA->id,
            $modelB->id
        ]);

        $deletedModelA = $repository->find($modelA->id);
        $deletedModelB = $repository->find($modelB->id);

        expect($deletedModelA)->toBeNull();
        expect($deletedModelB)->toBeNull();
    });
});
