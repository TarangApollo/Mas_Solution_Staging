<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProfileFiles extends Model
{
    use HasFactory;
    public $table = 'project_profile_files';
    protected $guard = 'callattendent';
    protected $fillable = [
        'projectProfileFilesId',
        'iTicketId',
        'projectProfileId',
        'strBOQUpload',
        'CompletionDocumentUpload',
        'iUserId',
        'strIP'
    ];
}
