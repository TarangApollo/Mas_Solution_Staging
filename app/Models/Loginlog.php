<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loginlog extends Model
{
    use HasFactory;
    public $table = 'loginlog';
     protected $fillable = [
        'userId',
        'action',
        "strEntryDate",
        "strIP"
    ];
}
