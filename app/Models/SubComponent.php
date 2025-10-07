<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubComponent extends Model
{
    use HasFactory;
    public $table = 'subcomponent';
    protected $fillable = [
        'iCompanyId',
        'iComponentId',
        'strSubComponent'
    ];
}
