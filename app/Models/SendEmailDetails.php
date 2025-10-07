<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SendEmailDetails extends Model
{
    public $table = 'sendemaildetails';
   
    protected $fillable = [
        'id', 
        'strSubject', 
        'strTitle',
        'strFromMail',
        'strCC',
        'strBCC'
    ];
}
