<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintUnregistered extends Model
{
    use HasFactory;

    protected $table = 'unregistered_complaint';

    protected $primaryKey = 'complaint_unregistered_ID';

    protected $fillable = [
        'userID',
        'violationID',
        'violationPrice',
        'dateSubmitted',
        'location',
        'description',
        'status',
        'id',
        'meetingDate',
        'reasonForDenying',
        'evidencePhoto',
        'plateNumber',
        'tricycleColor',
        'tricycleDescription',
        'resolutionDetail',
        'dateResolve',
        'paymentReceipt'
    ];

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'userID', 'userID');
    }


    public function violations()
    {
        return $this->belongsTo(Violation::class, 'violationID', 'violationID');
    }
}
