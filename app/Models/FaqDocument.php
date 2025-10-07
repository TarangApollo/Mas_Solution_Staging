<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqDocument extends Model
{
    use HasFactory;
    public $table = 'faqdocuments';
    protected $fillable = [
        'iFAQDocumentId',
        'iFAQId',
        'iCompanyId',
        'iDocumentType',
        'strFileName',
        'strEntryDate',
        'strIP'
    ];
}
