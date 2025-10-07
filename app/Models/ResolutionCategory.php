<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResolutionCategory extends Model
{
    use HasFactory;
    public $table = 'resolutioncategory';
    protected $fillable = [
        'iCompanyId',
        'strResolutionCategory',
        "strEntryDate",
        "strIP"
    ];
}
