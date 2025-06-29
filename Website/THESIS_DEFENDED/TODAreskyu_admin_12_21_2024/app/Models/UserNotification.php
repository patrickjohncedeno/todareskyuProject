<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_registered_id',
        'complaint_unregistered_id',
        'notification_type',
        'meeting_date',
        'denial_reason',
        'readNotif',
        'resolved',
        'unresolved',
        'resolution_date'
    ];

    public function registeredComplaint()
    {
        return $this->belongsTo(ComplaintRegistered::class, 'complaint_registered_id');
    }

    public function unregisteredComplaint()
    {
        return $this->belongsTo(ComplaintUnregistered::class, 'complaint_unregistered_id');
    }
}
