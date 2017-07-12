<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referee extends Model {

    protected $fillable = [
        'firstname', 'middlename', 'lastname'
    ];
    public $timestamps = false;

    public function matches() {
        return $this->hasMany(Match::class);
    }
      public function scopeTotal($query) {
        return $query->join('matches', 'referees.id', '=', 'matches.referee_id')
                        ->join('leagues', 'leagues.id', '=', 'matches.league_id');
    }


    public function getTotalAmountAttribute($dateFrom = null, $dateUntil = null) {
        $query = Referee::total()
                ->where('matches.referee_id', '=', $this->id);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        return $query->sum('leagues.referee_cost');
    }
  
    public function getNumberAttribute($dateFrom = null, $dateUntil = null) {
       $query = $this->matches();
         if ($dateFrom && $dateUntil) {
            $query = $query->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        return $query->count();
    }

}
