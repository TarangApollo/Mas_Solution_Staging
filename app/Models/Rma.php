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
        'OemCompannyId',
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
        'model_number',
        'Testing_result',
        'Testing_Comments',
        'Factory_rma_no',
        'Factory_Status',
        'Factory_Comments',
        'Cus_Comments',
        'iOEM_RMA_Id',
    ];
}
