<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'iso2'
    ];

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }

}
