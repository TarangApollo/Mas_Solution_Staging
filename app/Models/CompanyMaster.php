<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMaster extends Model
{
    use HasFactory;
    public $table = 'companymaster';
    protected $fillable = [
        'strOEMCompanyName',
        'strOEMCompanyId',
        'ContactPerson',
        'EmailId',
        'ContactNo',
        'Address1',
        'Address2',
        'Address3',
        'Pincode',
        'iStateId',
        'iCityId',
        'strGSTNo'
    ];
}
