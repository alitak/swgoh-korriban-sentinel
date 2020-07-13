<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Player
 * @package App\Models
 * @property int $id
 * @property string $user_name
 * @property string $user_code
 * @property string $ally_code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $mention
 * @property Snapshot $snapshots
 */
class Player extends Model
{

    /*
     |--------------------------------------------------------------------------
     | GLOBAL VARIABLES
     |--------------------------------------------------------------------------
    */
    protected $table = 'players';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;
    protected $guarded = ['id'];
    protected $fillable = [
        'user_name',
        'user_code',
        'ally_code',
    ];
    // protected $hidden = [];
    protected $dates = [
        'create_at',
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
    public function snapshots()
    {
        return $this->hasMany(Snapshot::class);
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

    public function getMentionAttribute()
    {
        return '<@' . $this->user_code . '>';
    }

    /*
      |--------------------------------------------------------------------------
      | MUTATORS
      |--------------------------------------------------------------------------
     */
}
