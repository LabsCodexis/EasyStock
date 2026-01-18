<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static orderBy(string $string, string $string1)
 * @method static create(array $array)
 */
class Entrada extends Model
{
    protected $table = 'entrada';
    protected $casts = [
        'itens' => 'array'
    ];

    protected $fillable = [
        'itens',
        'observacao',
        'valor_total'
    ];

}
