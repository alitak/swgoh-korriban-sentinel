<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryUnit extends Model
{
    /*
      |--------------------------------------------------------------------------
      | GLOBAL VARIABLES
      |--------------------------------------------------------------------------
     */

    protected $table = 'category_unit_pivot';
    protected $primaryKey = 'id';
    public $timestamps = FALSE;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'category_id',
        'unit_id',
    ];
    // protected $hidden = [];
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