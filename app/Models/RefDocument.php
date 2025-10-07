<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefDocument extends Model
{
    use HasFactory;
    public $table = 'refdocument';
    protected $fillable = [
        'iRefId',
        'iCompanyId',
        'iDocumentType',
        'strFileName',
        'strEntryDate',
        'strIP'
    ];
}
