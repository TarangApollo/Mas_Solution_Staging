<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyClientProfile extends Model
{
    use HasFactory;
    public $table = 'icompanyclientprofile';
    protected $fillable = [
        'iCompanyClientProfileId',
        'icompanyId',
        'strCompanyClientProfile',
        'strEntryDate',
        'strIP'
    ];
}
