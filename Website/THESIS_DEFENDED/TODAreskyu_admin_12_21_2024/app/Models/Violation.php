<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    use HasFactory;

    protected $table = 'tbl_violation';
    protected $fillable = [
        'violationName',
        'penalty'
    ];
    
    protected $primaryKey = 'violationID';

    public function registeredComplaints()
    {
        return $this->hasMany(ComplaintRegistered::class, 'violationID', 'violationID');
    }

    public function unregisteredComplaints()
    {
        return $this->hasMany(ComplaintUnregistered::class, 'violationID', 'violationID');
    }
}

