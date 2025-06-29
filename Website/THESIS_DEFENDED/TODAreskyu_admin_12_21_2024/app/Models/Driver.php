<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'tbl_driverinfo';
    protected $primaryKey = 'driverID';

    protected $fillable = [
        'driverName',
        'driverPhoneNum',
        'plateNumber',
        'tinPlate',
        'todaID',
        'qrCode'
    ];

    public function toda(){
        return $this->belongsTo(Toda::class, 'todaID', 'todaID');
    }
    public function complaints()
    {
        return $this->hasMany(ComplaintRegistered::class, 'driverID', 'driverID');
    }
}
