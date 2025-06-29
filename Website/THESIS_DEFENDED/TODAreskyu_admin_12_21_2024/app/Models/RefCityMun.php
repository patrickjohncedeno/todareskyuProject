<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefCityMun extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'refcitymun';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'psgcCode',
        'citymunDesc',
        'regDesc',
        'provCode',
        'citymunCode',
    ];

    // Relationship with Barangays
    public function barangays()
    {
        return $this->hasMany(RefBrgy::class, 'citymunCode', 'citymunCode');
    }
}
