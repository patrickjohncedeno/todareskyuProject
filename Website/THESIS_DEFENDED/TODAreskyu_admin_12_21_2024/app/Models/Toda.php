<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toda extends Model
{
    use HasFactory;

    protected $table = 'tbl_toda';
    protected $primaryKey = 'todaID';

    protected $fillable = [
        'todaName',
        'location',
        'contactNumber',
        'presidentName',
    ];
}
