<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = 'tbl_userinfo';
    protected $primaryKey = 'userID';

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'address',
        'age',
        'phoneNumber',
        'validID',
        'verified',
        'password'
    ];

    public function registeredComplaints()
    {
        return $this->hasMany(ComplaintRegistered::class, 'userID', 'userID');
    }

    public function unregisteredComplaints()
    {
        return $this->hasMany(ComplaintUnregistered::class, 'userID', 'userID');
    }
}
