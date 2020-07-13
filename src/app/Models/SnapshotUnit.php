<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SnapshotUnit
 * @package App\Models
 * @property int $snapshot_id
 * @property Snapshot $snapshot
 * @property int $unit_id
 * @property Unit $unit
 * @property int $power
 * @property int $rarity
 * @property int $gear_level
 * @property int $relic_tier
 * @property int $speed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SnapshotUnit extends Model
{
    /*
     |--------------------------------------------------------------------------
     | GLOBAL VARIABLES
     |--------------------------------------------------------------------------
    */

    protected $table = 'snapshot_units';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;
    protected $guarded = ['id'];
    protected $fillable = [
        'snapshot_id',
        'unit_id',
        'power',
        'rarity',
        'gear_level',
        'relic_tier',
        'speed',
    ];
    // protected $hidden = [];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [];

    // protected $hidden = [];
    // protected $dates = [];

    /*
      |--------------------------------------------------------------------------
      | FUNCTIONS
      |--------------------------------------------------------------------------
     */

    /*
      |--------------------------------------------------------------------------
      | RELATIONS
      |--------------------------------------------------------------------------
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function snapshot()
    {
        return $this->belongsTo(Unit::class);
    }

    /*
      |--------------------------------------------------------------------------
      | SCOPES
      |--------------------------------------------------------------------------
     */

    /*
      |--------------------------------------------------------------------------
      | ACCESORS
      |--------------------------------------------------------------------------
     */

    /*
      |--------------------------------------------------------------------------
      | MUTATORS
      |--------------------------------------------------------------------------
     */
}
