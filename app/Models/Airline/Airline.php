<?php

namespace App\Models\Airline;

use App\Models\Airfield\Terminal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Airline extends Model
{
    protected $fillable = [
        'icao_code',
        'name',
        'callsign',
        'is_cargo',
    ];

    protected $casts = [
        'is_cargo' => 'boolean',
    ];

    public function terminals(): BelongsToMany
    {
        return $this->belongsToMany(Terminal::class)->withTimestamps();
    }
}
