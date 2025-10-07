<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProfile extends Model
{
    use HasFactory;
    public $table = 'project_profile';
    // protected $guard = 'callattendent';
    protected $fillable = [
        'projectProfileId',
        'iTicketId',
        'projectName',
        'iStateId',
        'iCityId',
        'strVertical',
        'strSubVertical',
        'strSI',
        'strEngineer',
        'strCommissionedIn',
        'iSystemId',
        'strPanel',
        'strPanelQuantity',
        'strDevices',
        'strDeviceQuantity',
        'strOtherComponents',
        'strBOQ',
        'strAMC',
        'strOtherInformation',
        'strIP'
    ];
}
