<?php
// app/Models/Estoque.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Produto;

class Estoque extends Model
{
    protected $table = 'estoque';
    protected $guarded = [];


    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
