<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    use HasFactory;
    public $table = 'ticketdetail';
    protected $fillable = [
        "iTicketId",
        "iTicketLogId",
        "DocumentType",
        "DocumentName",
        "isAdditional",
        "iStatus",
        "isDelete",
        "strEntryDate",
        "strIP",
        'aws_identifier'
    ];
}
