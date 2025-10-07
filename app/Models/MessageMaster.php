<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageMaster extends Model
{
    use HasFactory;
    public $table = 'messagemaster';
     protected $fillable = [
        'iMessageId',
        'strMessage',
        'iStatusId',
        'iCompanyId',
        'toCustomer',
        'toCompany',
        'toExecutive',
        'wApptoCustomer',
        'wApptoExecutive',
        "iStatus",
        "isDelete",
        'strEntryDate',
        'strIP'
    ];
}
