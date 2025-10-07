<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class infoTable extends Model
{
    use HasFactory;
    public $table = 'infotable';
     protected $fillable = [
        'tableName',
        'tableAutoId',
        'iOemCompanyId',
        'tableMainField',
        'action',
        'actionBy',
        'strEntryDate',
    ];
}
