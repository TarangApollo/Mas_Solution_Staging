<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rma extends Model
{
    use HasFactory;
    public $table = 'rma';
    protected $fillable = [
        'rma_id',
        'iComplaintId',
        'iRMANumber',
        'strCustomerCompany',
        'strCustomerEngineer',
        'strDistributor',
        'strProjectName',
        'strRMARegistrationDate',
        'strItem',
        'strItemDescription',
        'strSerialNo',
        'strDateCode',
        'strInwarranty',
        'strQuantity',
        'strSelectSystem',
        'strFaultdescription',
        'strFacts',
        'strAdditionalDetails',
        'strMaterialReceived',
        'strMaterialReceivedDate',
        'strTesting',
        'strTestingCompleteDate',
        'strFaultCoveredinWarranty',
        'strReplacementApproved',
        'strReplacementReason',
        'strFactory_MaterialReceived',
        'strFactory_MaterialReceivedDate',
        'strFactory_Testing',
        'strFactory_TestingCompleteDate',
        'strFactory_FaultCoveredinWarranty',
        'strFactory_ReplacementApproved',
        'strFactory_ReplacementReason',
        'strMaterialDispatched',
        'strMaterialDispatchedDate',
        'strStatus',
        'iStatus',
        'isDelete',
        'created_at',
        'updated_at',
        'strIP',
        'rma_enter_by',
    ];
}
