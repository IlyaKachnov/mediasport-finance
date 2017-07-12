<?php

namespace App\Models;

use App\Models\Match;
use App\Models\EntranceFee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Team extends Model {

    protected $fillable = [
        'name', 'balance'
    ];
    public $timestamps = false;

    public function league() {
        return $this->belongsTo(League::class);
    }

    public function prepays() {
        return $this->hasMany(Prepay::class);
    }

    public function matchesAsHome() {
        return $this->hasMany(Match::class, 'home_id');
    }

    public function matchesAsGuest() {
        return $this->hasMany(Match::class, 'guest_id');
    }

    public function fee() {
        return $this->hasOne(EntranceFee::class, 'team_id');
    }

    public function debts() {
        return $this->hasMany(Debt::class);
    }

    public function scopeAvailable($query, $leagueId) {
        return $query->doesntHave('fee')->where('league_id', '=', $leagueId);
    }

    public static function getTeamsInLeague($league = null) {
        if ($league) {
            return Team::where('league_id', '=', $league)->get();
        } else
            return Team::all(); //should be with()
    }

    public function getTotalPrepaysAttribute() {
        return $this->prepays()->sum('amount');
    }

    /**
     * Get expenses depending on method
     * @param type $dateFrom
     * @param type $dateUntil
     * @return type int
     */
    public function getExpenses($dateFrom, $dateUntil, $method) {
        $totalQuery = '';
        foreach ($this->matchesAsHome as $match) {
            if ($match->hasPhoto && $match->hasPhoto->name == $method) {
                $totalQuery .= '+photo_cost';
            }
            if ($match->hasVideo && $match->hasVideo->name == $method) {
                $totalQuery .= '+ video_cost';
            }
            if ($match->hasDoctor && $match->hasDoctor->name == $method) {
                $totalQuery .= '+ doctor_cost';
            }
            if ($match->videoEdit && $match->videoEdit->name == $method) {
                $totalQuery .= '+ edit_cost';
            }
            if ($match->otherMethod && $match->otherMethod->name == $method) {
                $totalQuery .= '+ other';
            }
            if ($match->refereeMethod->name == $method) {
                $totalQuery .= '+ referee_cost';
            }
        }
        $query = Match::home();
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        if ($totalQuery) {
            return $query->where('home_id', '=', $this->id)->sum(DB::raw($totalQuery));
        } else {
            return 0;
        }
    }

    /* public function getCashlessExpensesAttribute($dateFrom = null, $dateUntil = null) {

      return $this->getExpenses($dateFrom, $dateUntil, 'безнал', 'rent_price + ');
      } */

    public function getCashlessExpensesAttribute($dateFrom = null, $dateUntil = null) {


        $totalQuery = '';
        $rentPrice = 0;
        $total = 0;
        $method = 'безнал';
        foreach ($this->matchesAsHome as $match) {

            if ($match->hasPhoto && $match->hasPhoto->name == $method) {
                $totalQuery .= '+photo_cost';
            }
            if ($match->hasVideo && $match->hasVideo->name == $method) {
                $totalQuery .= '+ video_cost';
            }
            if ($match->hasDoctor && $match->hasDoctor->name == $method) {
                $totalQuery .= '+ doctor_cost';
            }
            if ($match->videoEdit && $match->videoEdit->name == $method) {
                $totalQuery .= '+ edit_cost';
            }
            if ($match->otherMethod && $match->otherMethod->name == $method) {
                $totalQuery .= '+ other';
            }
            if ($match->refereeMethod->name == $method) {
                $totalQuery .= '+ referee_cost';
            }
            $rentPrice += $match->getRentPrice();
        }
        $query = Match::home();
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        if ($totalQuery) {
            $total = $rentPrice + $query->where('home_id', '=', $this->id)->sum(DB::raw($totalQuery));
        } else {
            $total = $rentPrice;
        }
        return $total;
    }

    public function getCashExpensesAttribute($dateFrom = null, $dateUntil = null) {

        return $this->getExpenses($dateFrom, $dateUntil, 'нал');
    }

    public function getCardExpensesAttribute($dateFrom = null, $dateUntil = null) {
        return $this->getExpenses($dateFrom, $dateUntil, 'карта');
    }

    public function getTotalExpensesAttribute() {
        return $this->cash_expenses + $this->cashless_expenses + $this->card_expenses;
    }

    /**
     * 
     * fees as incomes in home or guest match
     */
    public function getIncomes($method, $dateFrom, $dateUntil, $fees) {
        $totalFees = 0;
        $matchesHome = $this->matchesAsHome;
        $matchesGuest = $this->matchesAsGuest;
        $homeSum = $matchesHome->where('home_method_id', $method);
        $guestSum = $matchesGuest->where('guest_method_id', $method);
        if ($dateFrom && $dateUntil) {
            $homeSum = $homeSum->where('match_date', '>=', $dateFrom)->where('match_date', '<=', $dateUntil);
            $guestSum = $guestSum->where('match_date', '>=', $dateFrom)->where('match_date', '<=', $dateUntil);
        }
        if ($fees) {
            if ($method == 1) {
                $totalFees = $this->getCashFeesAttribute($dateFrom, $dateUntil);
            } elseif ($method == 2) {
                $totalFees = $this->getCashlessFeesAttribute($dateFrom, $dateUntil);
            } else {
                $totalFees = $this->getCardFeesAttribute($dateFrom, $dateUntil);
            }
        }
        $sum = $guestSum->sum('guest_fee') + $homeSum->sum('home_fee');
        return $sum + $totalFees;
    }

    public function getCashIncomesAttribute($dateFrom = null, $dateUntil = null, $fees = false) {
        return $this->getIncomes(1, $dateFrom, $dateUntil, $fees);
    }

    public function getCashlessIncomesAttribute($dateFrom = null, $dateUntil = null, $fees = false) {
        return $this->getIncomes(2, $dateFrom, $dateUntil, $fees);
    }

    public function getCardIncomesAttribute($dateFrom = null, $dateUntil = null, $fees = false) {
        return $this->getIncomes(3, $dateFrom, $dateUntil, $fees);
    }

    public function getTotalIncomesAttribute() {
        return $this->cash_incomes + $this->cashless_incomes + $this->card_incomes;
    }

    /**
     * Get total fees 
     * fees could be on/off
     */
    public function getFees($dateFrom, $dateUntil, $method) {
        $query = EntranceFee::fee()->where([['team_id', '=', $this->id], ['payment_methods.name', '=', $method]]);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['created_at', '>=', $dateFrom], ['created_at', '<=', $dateUntil]]);
        }

        return $query->sum('paid_fee');
    }

    public function getCashFeesAttribute($dateFrom = null, $dateUntil = null) {
        return $this->getFees($dateFrom, $dateUntil, 'нал');
    }

    public function getCashlessFeesAttribute($dateFrom = null, $dateUntil = null) {
        return $this->getFees($dateFrom, $dateUntil, 'безнал');
    }

    public function getCardFeesAttribute($dateFrom = null, $dateUntil = null) {
        return $this->getFees($dateFrom, $dateUntil, 'карта');
    }

    public function getTotalAttribute() {
        return $this->total_incomes - $this->total_expenses;
    }

}
