<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{

    protected $fillable = [
        'firstname', 'middlename', 'lastname'
    ];

    public $timestamps = false;

    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    /**
     * @param Carbon|null $dateFrom
     * @param Carbon|null $dateUntil
     * @return int
     */
    public function getTotalAmountAttribute(Carbon $dateFrom = null, Carbon $dateUntil = null) : int
    {
        $query = Referee::join('matches', 'referees.id', '=', 'matches.referee_id')
            ->join('leagues', 'leagues.id', '=', 'matches.league_id')
            ->where('matches.referee_id', '=', $this->id);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        return $query->sum('leagues.referee_cost');
    }

    /**
     * @param Carbon|null $dateFrom
     * @param Carbon|null $dateUntil
     * @return int
     */
    public function getNumberAttribute(Carbon $dateFrom = null, Carbon $dateUntil = null) : int
    {
        $query = $this->matches();
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        return $query->count();
    }

}
