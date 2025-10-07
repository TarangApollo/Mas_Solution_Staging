<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmaDetail extends Model
{
    use HasFactory;
    public $table = 'rma_detail';
    protected $fillable = [
        'rma_detail_id',
        'rma_id',
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
        'Additional_rma_model_number',
        'Additional_Testing_result',
        'Additional_Testing_Comments',
        'Additional_Factory_rma_no',
        'Additional_Factory_Status',
        'Additional_Factory_Comments',
        'Additional_Cus_Comments',
    ];
}
