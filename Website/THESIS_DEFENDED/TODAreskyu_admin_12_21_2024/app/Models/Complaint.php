<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'tbl_complaint';
    protected $primaryKey = 'complaintID';

    protected $fillable = [
        'userID',
        'driverID',
        'dateSubmitted',
        'location',
        'description',
        'status',
        'id',
        'violationID',
        'resolutionDetail',
        'dateResolve'
    ];

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'userID', 'userID');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driverID', 'driverID');
    }

    public function violations()
    {
        return $this->belongsTo(Violation::class, 'violationID', 'violationID');
    }
}
