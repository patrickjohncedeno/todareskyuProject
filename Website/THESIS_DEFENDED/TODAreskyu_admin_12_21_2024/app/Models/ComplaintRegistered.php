<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintRegistered extends Model
{
    use HasFactory;

    protected $table = 'registered_complaint';

    protected $primaryKey = 'complaint_registered_ID';

    protected $fillable = [
        'userID',
        'driverID',
        'violationID',
        'violationPrice',
        'dateSubmitted',
        'location',
        'description',
        'status',
        'id',
        'meetingDate',
        'reasonForDenying',
        'resolutionDetail',
        'dateResolve',
        'paymentReceipt'
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
