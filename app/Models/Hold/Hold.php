<?php
namespace App\Models\Hold;

use App\Models\Navigation\Navaid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model for a hold
 *
 * Class Hold
 * @package App\Models\Squawks
 */
class Hold extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fix',
        'inbound_heading',
        'minimum_altitude',
        'maximum_altitude',
        'turn_direction',
        'description',
    ];

    public function navaid(): BelongsTo
    {
        return $this->belongsTo(Navaid::class);
    }

    /**
     * Relationship between a hold and its restrictions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function restrictions()
    {
        return $this->hasMany(HoldRestriction::class, 'hold_id', 'id');
    }
}
