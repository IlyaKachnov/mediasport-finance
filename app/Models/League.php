<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model {

    protected $fillable = ['name',
        'total_fees',
        'referee_cost',
    ];
    public $timestamps = false;

    public function teams() {
        return $this->hasMany(Team::class);
    }

    public function matches() {
        return $this->hasMany(Match::class);
    }

    public function gyms() {
        return $this->belongsToMany(Gym::class)->withPivot('rent_price');
    }

}
