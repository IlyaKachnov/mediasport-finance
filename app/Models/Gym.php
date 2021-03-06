<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Match;
use App\Models\DayExpense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class Gym extends Model
{

    protected $fillable = [
        'name', 'address', 'rent'
    ];

    public $timestamps = false;

    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    public function dayExpenses()
    {
        return $this->hasMany(DayExpense::class);
    }
    /*
     * 
     * Start methods for matches page
     * 
     */
    /**
     * get today's matches for selected gym
     */
    public function getTodayMatches()
    {
        return $this->matches()
            ->whereMatchDate(Carbon::today())
            ->get();
    }

    public function getTodayExpenses()
    {
        return $this->dayExpenses()
            ->where('day', Carbon::today())
            ->get();
    }

    public function getMatchesOnDate(Carbon $date)
    {
        return $this->matches()
            ->whereMatchDate($date)
            ->get();
    }

    public function getExpensesOnDate(Carbon $date)
    {
        return $this->dayExpenses()
            ->where('day', $date)
            ->get();
    }

    public function getDayIncomesAttribute(Carbon $date = null)
    {
        $total = 0;
        if ($date) {
            $matches = $this->getMatchesOnDate($date);
        } else {
            $matches = $this->getTodayMatches();
        }
        if ($matches->isNotEmpty()) {
            foreach ($matches as $match) {
                $total += $match->home_fee + $match->guest_fee;
            }
        }
        return $total;
    }

    public function getRefereeCost(Carbon $date = null)
    {
        $total = 0;
        if ($date) {
            $matches = $this->getMatchesOnDate($date);
        } else {
            $matches = $this->getTodayMatches();
        }
        foreach ($matches as $match) {
            $total += $match->league->referee_cost;
        }
        return $total;
    }

    public function getDayExpenseAttribute(Carbon $date = null)
    {
        $total = 0;
        if ($date) {
            $dExp = $this->getExpensesOnDate($date);
        } else {
            $dExp = $this->getTodayExpenses();
        }
        if ($dExp->isNotEmpty()) {
            $dayExps = $dExp->first();
            $total = $dayExps->photo_cost + $dayExps->video_cost + $dayExps->edit_cost
                + $dayExps->rent_cost + $dayExps->doctor_cost + $dayExps->curator_cost + $dayExps->other;
        }
        return $total + $this->getRefereeCost($date);
    }

    public function getDayDebtAttribute(Carbon $date = null)
    {
        $totalDebt = 0;
        if ($date) {
            $matches = $this->getMatchesOnDate($date);
        } else {
            $matches = $this->getTodayMatches();
        }
        foreach ($matches as $match) {
            foreach ($match->debts as $debt) {
                $totalDebt += $debt->debt_amount;
            }
        }
        return $totalDebt;
    }

    public function getDayTotalAttribute(Carbon $date = null)
    {
        return $this->getDayIncomesAttribute($date) - $this->getDayExpenseAttribute($date);
    }

    public function getDayMatchesBalance($date)
    {
        $incomes = $this->getDayIncomesAttribute($date);
        $expenses = $this->getDayExpenseAttribute($date);
        $debt = $this->getDayDebtAttribute($date);
        $total = $this->getDayTotalAttribute($date);
        return compact('incomes', 'expenses', 'debt', 'total');
    }

    public function leagues()
    {

        return $this->belongsToMany(League::class)->withPivot('rent_price');
    }

    public function scopeAmount($query)
    {
        return $query->join('matches', 'gyms.id', '=', 'matches.gym_id');
    }

    public function scopeExpenses($query)
    {
        return $query->amount()->join('leagues', 'leagues.id', '=', 'matches.league_id');
    }

    public static function getAvailableGyms($leagueId)
    {
        $query = DB::table('gym_league')->where('league_id', $leagueId)->pluck('gym_id');
        return self::whereNotIn('id', $query)->get();
    }

    public static function getEditGyms($leagueId, $gymId)
    {
        $query = DB::table('gym_league')->where('league_id', $leagueId)->where('gym_id', '<>', $gymId)->pluck('gym_id');
        return self::whereNotIn('id', $query)->get();
    }
    /**
     *
     *
     * Methods for report page
     *
     */
//count

    public function getNumberAttribute(Carbon $dateFrom = null, Carbon $dateUntil = null)
    {
        $query = $this->matches();
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        return $query->count();
    }

    //аренда
    public function getTotalRentAttribute($dateFrom = null, $dateUntil = null)
    {
        $rentPrice = 0;
        $dayExpenses = $this->dayExpenses;
        if ($dateFrom && $dateUntil) {
            $dayExpenses = $this
                ->dayExpenses()
                ->where([['day', '>=', $dateFrom], ['day', '<=', $dateUntil]]);
        }

        foreach ($dayExpenses as $dExp) {
            $rentPrice += $dExp->rent_cost;
        }
        return $rentPrice;
    }

    //public function getRentOfDateAttribute()
    //сдано
    public function getAmountFeesAttribute(Carbon $dateFrom = null, Carbon $dateUntil = null)
    {
        $query = Gym::join('matches', 'gyms.id', '=', 'matches.gym_id')->where('gym_id', '=', $this->id);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }

        return(int) $query->sum(DB::raw('home_fee + guest_fee'));
    }

    //затраты
    public function getTotalExpensesAttribute(Carbon $dateFrom = null, Carbon $dateUntil = null)
    {

        $totalQuery = 'curator_cost + rent_cost';
        foreach ($this->dayExpenses as $dExp) {
            if ($dExp->has_photo) {
                $totalQuery .= '+ photo_cost';
            }
            if ($dExp->has_video) {
                $totalQuery .= '+ video_cost';
            }
            if ($dExp->has_doctor) {
                $totalQuery .= '+ doctor_cost';
            }
            if ($dExp->video_edit) {
                $totalQuery .= '+ edit_cost';
            }
            if ($dExp->other) {
                $totalQuery .= '+ other';
            }
        }
        $query = self::join('day_expenses', 'gyms.id', '=', 'day_expenses.gym_id')
            ->where('gym_id', '=', $this->id);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['day', '>=', $dateFrom], ['day', '<=', $dateUntil]]);
        }
        return (int)$query->sum(DB::raw($totalQuery)) + $this->getRefereeExpenses($dateFrom, $dateUntil);
    }

    //разница
    public function getDifferenceAttribute()
    {
        return $this->amount_fees - $this->total_expenses - $this->total_rent;
    }

    //total row 
    /**
     *
     * @param type $dateFrom
     * @param type $dateUntil
     * @return int
     * Get total number of gyms
     */
//    public static function getSumNumber($dateFrom = null, $dateUntil = null) {
//        dd(Match::count());
//        return Match::count();
//    }

    /*
     * get total rent sum for all gyms with matches
     */


    public static function getSumRent()
    {
        $totalSum = 0;
        $gyms = Gym::all();
        foreach ($gyms as $gym) {
            $totalSum += $gym->getTotalRentAttribute();
        }
        return $totalSum;
    }

    /**
     *
     * @return type
     * get total fees for all gyms
     */
    public static function getSumFees()
    {
        return (int)Match::sum(DB::raw('home_fee + guest_fee'));
    }

    /*
     * 
     * get total expenses for all gyms
     */

    public static function getSumExpenses()
    {
        $total = 0;
        $gyms = Gym::all();
        foreach ($gyms as $gym) {
            $total += $gym->getTotalExpensesAttribute();
        }
        return $total;
    }

    public static function getTotalDifference()
    {
        return self::getSumFees() - self::getSumExpenses() - self::getSumRent();
    }

    public function getRefereeExpenses(Carbon $dateFrom = null, Carbon $dateUntil = null)
    {
        $total = 0;
        $allMatches = $this->matches();
        if ($dateFrom && $dateUntil) {
            $allMatches = $allMatches->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        $matches = $allMatches->get();
        foreach ($matches as $match) {
            $total += $match->league->referee_cost;
        }
        return $total;
    }
}
