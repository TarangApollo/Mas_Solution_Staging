<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;
    public $table = 'companydistributor';
    protected $fillable = [
        'iCompanyId',
        'Name',
        'EmailId',
        'distributorPhone',
        'Address',
        'iStateId',
        'iCityId',
        'branchOffice'
    ];
}
