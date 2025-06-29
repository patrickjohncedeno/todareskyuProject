<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefBrgy extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'refbrgy';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'brgyCode',
        'brgyDesc',
        'regCode',
        'provCode',
        'citymunCode',
    ];

    // Relationship with City/Municipality
    public function cityMunicipality()
    {
        return $this->belongsTo(RefCityMun::class, 'citymunCode', 'citymunCode');
    }
}
