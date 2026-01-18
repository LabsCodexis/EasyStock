<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $string)
 * @method static findOrFail(mixed $produto_id)
 */
class Produto extends Model
{
    protected $table = 'produtos';

    protected $guarded = [];
}
