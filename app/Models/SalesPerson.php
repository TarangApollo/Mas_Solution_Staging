<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPerson extends Model
{
    use HasFactory;
    public $table = 'salesperson';
    protected $fillable = [
        'salesPersonName',
        'salesPersonNumber',
        'salesPersonEmail',
        'salesPesonType',
        'entryBy',
        'strIP'
    ];
}
