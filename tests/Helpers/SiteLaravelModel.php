<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteLaravelModel extends Model
{
    use SoftDeletes;

    protected $table = 'sites';
    protected $fillable = ['code'];
}
