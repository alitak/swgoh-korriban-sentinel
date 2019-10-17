<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    /*
     |--------------------------------------------------------------------------
     | GLOBAL VARIABLES
     |--------------------------------------------------------------------------
    */

    protected $table = 'snapshots';
    protected $primaryKey = 'id';
    public $timestamps = TRUE;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'base_id',
        'image',
        'alignment',
        'role',
    ];
    // protected $hidden = [];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

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
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d');
    }

    /*
      |--------------------------------------------------------------------------
      | MUTATORS
      |--------------------------------------------------------------------------
     */
}
