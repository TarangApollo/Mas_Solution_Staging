<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueType extends Model
{
    use HasFactory;
    public $table = 'issuetype';
    protected $fillable = [
        'iCompanyId',
        'strIssueType',
        'strIP',
        'strEntryDate'
    ];
}
