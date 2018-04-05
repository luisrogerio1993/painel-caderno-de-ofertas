<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log_delete_validade extends Model
{
    protected $table = "log_delete_validades";
    protected $fillable = [
        'item',
        'descricao',
    ];
    
}
