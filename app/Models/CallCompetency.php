<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallCompetency extends Model
{
    use HasFactory;
    public $table = 'callcompetency';
    protected $fillable = [
        'iCompanyId',
        'strCallCompetency',
        'strIP',
        'strEntryDate'
    ];
}
