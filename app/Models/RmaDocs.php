<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmaDocs extends Model
{
    use HasFactory;
    public $table = 'rma_docs';
    protected $fillable = [
        'rma_docs_id',
        'rma_id',
        'rma_detail_id',
        'strImages',
        'strVideos',
        'strDocs',
        'Factory_strDocs',
        'Additional_Factory_strDocs',
        'iStatus',
        'isDelete',
        'created_at',
        'updated_at',
        'strIP'
    ];
}
