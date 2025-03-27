<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

// pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}


function prepareSqliteInMemory(?PDO $conn): void
{
    $now = date('Y-m-d H:i:s');
    $schema = <<<SQL
        DROP TABLE IF EXISTS `sites`;

        CREATE TABLE IF NOT EXISTS `sites` (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            code VARCHAR(25) NOT NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            deleted_at DATETIME NULL
        );


        INSERT INTO sites(`code`, `created_at`, `updated_at`)
        VALUES
            ('code-1', '{$now}', '{$now}'),
            ('code-2', '{$now}', '{$now}');
    SQL;

    $conn->exec($schema);
}

function freeSqliteInMemory(?PDO $conn): void
{
    $schema = <<<SQL
        DROP TABLE IF EXISTS `sites`;
    SQL;

    $conn->exec($schema);
}

function getPropertyValue(object $object, string $property): mixed
{
    $reflectedClass = new ReflectionClass($object);
    $reflection = $reflectedClass->getProperty($property);
    $reflection->setAccessible(true);
    return $reflection->getValue($object);
}
