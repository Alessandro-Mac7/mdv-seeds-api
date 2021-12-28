<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSeeds extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seed_id', 'user_id', 'pick_date'
    ];

    /**
     * Get the discounts for the product.
     */
    public function seed()
    {
        return $this->belongsTo(Seed::class);
    }

    /**
     * Get the discounts for the product.
     */
    public function user()
    {
        return $this->belongsTo(Users::class);
    }
}
