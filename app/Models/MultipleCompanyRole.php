<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleCompanyRole extends Model
{
    use HasFactory;
    public $table = 'multiplecompanyrole';
    protected $guard = 'callattendent';
    protected $fillable = [
        'id',
        'icallattendent',
        'userid',
        'iExecutiveLevel',
        'iOEMCompany',
        'iRoleId',
        'strIP'
    ];
}
