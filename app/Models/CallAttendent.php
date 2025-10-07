<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallAttendent extends Model
{
    use HasFactory;
    public $table = 'callattendent';
    protected $guard = 'callattendent';
    protected $fillable = [
        'strFirstName',
        'strLastName',
        'strContact',
        'strEmailId',
        'strPassword',
        'salt'
    ];
}
