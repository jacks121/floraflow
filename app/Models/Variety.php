<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variety extends Model
{
    protected $fillable = ['name', 'variety_number'];

    public function bottles()
    {
        return $this->hasMany(Bottle::class, 'variety_number', 'variety_number');
    }
}
