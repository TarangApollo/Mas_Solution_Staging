<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalPerson extends Model
{
    use HasFactory;
    public $table = 'technicalperson';
    protected $fillable = [
        'technicalPersonName',
        'technicalPersonNumber',
        'technicalPersonEmail',
        'technicalPesonType',
        'entryBy',
        'strIP'
    ];
}
