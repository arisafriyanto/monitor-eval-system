<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name',
        'recipient_count',
        'province_code',
        'city_code',
        'district_code',
        'distribution_date',
        'evidence_file',
        'notes',
        'status',
        'rejection_reason'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }
}
