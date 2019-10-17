<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    /*
      |--------------------------------------------------------------------------
      | GLOBAL VARIABLES
      |--------------------------------------------------------------------------
     */

    protected $table = 'characters';
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
    public function categories()
    {
        return $this->belongsToMany(Category::class);
//        return $this->hasManyThrough($related, $through, $firstKey, $secondKey, $localKey, $secondLocalKey);
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
