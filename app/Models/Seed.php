<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seed extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'color', 'code'
    ];
    
    /**
     * Get the user that owns the address.
     */
    public function userSeeds()
    {
        return $this->hasMany(UserSeeds::class);
    }
}
