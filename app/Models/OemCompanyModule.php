<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OemCompanyModule extends Model
{
    use HasFactory;
    public $table = 'oem_company_modules';
    protected $fillable = [
        'id',
        'iModuleId',
        'iOEMCompany'
    ];
}
