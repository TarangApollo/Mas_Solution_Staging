<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleMaster extends Model
{
    use HasFactory;
    public $table = 'module_masters';
    protected $fillable = [
        'id',
        'module_name'
    ];
}
