<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMaster extends Model
{
    use HasFactory;
    public $table = 'ticketmaster';
    protected $fillable = [
        "iCustomerComplainUserId",
        "iOEMTicketId",
        "strTicketUniqueID",
        "CustomerMobile",
        "CustomerName",
        "CustomerEmail",
        "OemCompannyId",
        "iCompanyId",
        "iCompanyProfileId",
        "CustomerEmailCompany",
        "OtherInformation",
        "iDistributorId",
        "ProjectName",
        "iStateId",
        "iCityId",
        "iCallThrough",
        "UserDefiine1",
        "iSystemId",
        "iComnentId",
        "iSubComponentId",
        "iSupportType",
        "issue",
        "Resolutiondetail",
        "iResolutionCategoryId",
        "iIssueTypeId",
        "CallerCompetencyId",
        "iTicketStatus",
        "finalStatus",
        "LevelId",
        "iCallAttendentId",
        "oldStatus",
        "oldStatusDatetime",
        "iLevel2CallAttendentId",
        'strIP',
        'strEntryDate',
        'aws_identifier'
    ];
}
