<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WlUser extends Model
{
    use HasFactory;
    public $table = 'companyuser';
    protected $guard = 'companyuser';
    protected $fillable = [
        'iCompanyId',
        'strFirstName',
        'strLastName',
        'strContact',
        'strEmail',
        'strPassword',
        'strSalt',
        'iRoleId',
        'tIsSuperAdmin',
        'iUserId'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
