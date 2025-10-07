<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    use HasFactory;
    public $table = 'ticketlog';
    protected $fillable = [
        "iticketId",
        "customerNumber",
        "iStatus",
        "oldStatus",
        "oldStatusDatetime",
        "LevelId",
        "iCallAttendentId",
        "iResolutionCategoryId",
        "iIssueTypeId",
        "newResolution",
        "comments",
        "iEntryBy",
        "strEntryDate",
        "strIP"
    ];
}
