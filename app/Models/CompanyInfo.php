<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;
    public $table = 'companyinfo';
    protected $fillable = [
        'strCompanyName',
        'iCompanyId',
        'Address1',
        'address2',
        'strCity',
        'strState',
        'strCountry',
        'Pincode',
        'Phone',
        'ContactNo',
        'EmailId',
        'AnotherEmailId',
        'facebookLink',
        'instaLink',
        'twitterlink',
        'linkedinlink',
        'strLogo',
        'headerColor',
        'menuColor',
        'menubgColor'
    ];
}
