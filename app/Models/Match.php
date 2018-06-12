<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Income;
use App\Models\DayExpense;
use App\Models\Consumption;
use App\Models\Debt;

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
// Ignores notices and reports all other kinds... and warnings
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
// error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}

class Match extends Model
{

    protected $fillable = ['home_fee', 'guest_fee', 'match_date'];
    public $timestamps = false;

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_id');
    }

    public function guestTeam()
    {
        return $this->belongsTo(Team::class, 'guest_id');
    }

    public function homeMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'home_method_id');
    }

    public function guestMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'guest_method_id');
    }

    public function referee()
    {
        return $this->belongsTo(Referee::class);
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function refereeMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'referee_method_id');
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function setMatchDateAttribute(string $value)
    {
        $this->attributes['match_date'] = !empty($value) ? \Carbon\Carbon::createFromFormat('d-m-Y', $value) : null;
    }

    public function scopeWithAll($query)
    {
        $query->with(['hasPhoto', 'league',
            'hasVideo',
            'videoEdit',
            'hasDoctor',
            'otherMethod',
            'refereeMethod',]);
    }

    /**
     * Get teams availiable for selected home team
     * @param type $id
     * @return array
     *
     */
    public function editMatchTeams(int $leagueId)
    {
        $query = self::unavailable()
            ->where([['home_id', '=', $this->home_id], ['guest_id', '<>', $this->guest_id], ['matches.league_id', '=', $leagueId]])
            ->pluck('id');
        return Team::whereNotIn('id', $query)
            ->where([['id', '<>', $this->home_id], ['teams.league_id', '=', $leagueId]])
            ->get();
    }

    public static function getAvailableTeams(int $leagueId, int $teamId)
    {
        $query = self::unavailable()
            ->where([['home_id', '=', $teamId], ['matches.league_id', '=', $leagueId]])
            ->pluck('id');
        return Team::whereNotIn('id', $query)
            ->where([['id', '<>', $teamId], ['teams.league_id', '=', $leagueId]])
            ->orderBy('name', 'asc')
            ->get();
    }

    public function scopeHome($query)
    {
        return $query->join('leagues', 'leagues.id', '=', 'matches.league_id');
    }

    public function scopeUnavailable($query)
    {
        return $query->select('teams.id')
            ->join('teams', 'guest_id', '=', 'teams.id');
    }

    public function scopeHomeIncomes($query)
    {
        return $query->join('payment_methods as home', 'home.id', '=', 'matches.home_method_id');
    }

    public function scopeGuestIncomes($query)
    {
        return $query->join('payment_methods as guest', 'guest.id', '=', 'matches.guest_method_id');
    }

    /**
     *
     * @param type $league array
     * @return type Match
     */
    public static function getMatchesInLeague($leagues = null, $dateFrom = null, $dateUntil = null)
    {
        if ($leagues) {
            $matches = self::whereIn('league_id', $leagues);
            if ($dateFrom && $dateUntil) {
                $matches->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
            }
            return $matches->get();
        } elseif ($dateFrom && $dateUntil) {
            $matches = self::where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
            return $matches->withAll()->get();
        } else {
            return self::withAll()->get();
        }
    }

    public function getRentPrice()
    {
        return DB::table('gym_league')
            ->where([['gym_id', $this->gym_id], ['league_id', $this->league_id]])
            ->value('rent_price');
    }

    /**
     * methods for total report
     */
    /*
     * incomes cash/cashless methods
     */
    public function getIncomes($method, $dateFrom, $dateUntil, $gyms, $fees, $common)
    {
        $income = new Income();
        $totalFees = 0;
        $commonIncomes = 0;
        $homeQuery = self::homeIncomes();
        $guestQuery = self::guestIncomes();

        if ($dateFrom && $dateUntil) {
            $homeQuery = $homeQuery->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
            $guestQuery = $guestQuery->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        if ($gyms) {
            $homeQuery = $homeQuery->whereIn('gym_id', $gyms);
            $guestQuery = $guestQuery->whereIn('gym_id', $gyms);
        }
        if ($fees) {
            if ($method == 'нал') {
                $totalFees = $this->getCashFeesAttribute($dateFrom, $dateUntil);
            } elseif ($method == 'безнал') {
                $totalFees = $this->getCashlessFeesAttribute($dateFrom, $dateUntil);
            } else {
                $totalFees = $this->getCardFeesAttribute($dateFrom, $dateUntil);
            }
        }
        if ($common) {
            if ($method == 'нал') {
                $commonIncomes = $income->getAmountCashAttribute($dateFrom, $dateUntil);
            } elseif ($method == 'безнал') {
                $commonIncomes = $income->getAmountCashlessAttribute($dateFrom, $dateUntil);
            } else {
                $commonIncomes = $income->getAmountCardAttribute($dateFrom, $dateUntil);
            }
        }

        return $homeQuery->where('home.name', '=', $method)
                ->sum('home_fee') +
            $guestQuery->where('guest.name', '=', $method)
                ->sum('guest_fee') + $totalFees + $commonIncomes;
    }

    public function getCashIncomesAttribute($dateFrom = null, $dateUntil = null, $gyms = null, $fees = false, $common = false)
    {
        return $this->getIncomes('нал', $dateFrom, $dateUntil, $gyms, $fees, $common) +
            $this->getIncomes('с/о', $dateFrom, $dateUntil, $gyms, $fees, $common);
    }

    public function getCashlessIncomesAttribute($dateFrom = null, $dateUntil = null, $gyms = null, $fees = false, $common = false)
    {
        return $this->getIncomes('безнал', $dateFrom, $dateUntil, $gyms, $fees, $common);
    }

    public function getCardIncomesAttribute($dateFrom = null, $dateUntil = null, $gyms = null, $fees = false, $common = false)
    {
        return $this->getIncomes('карта', $dateFrom, $dateUntil, $gyms, $fees, $common);
    }

    public function getTotalIncomesAttribute()
    {
        return $this->cashless_incomes + $this->cash_incomes + $this->card_incomes;
    }

    /**
     *
     * fees could be on/off
     */
    public function getFees($dateFrom, $dateUntil, $method)
    {
        $query = EntranceFee::fee()->where('payment_methods.name', '=', $method);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['created_at', '>=', $dateFrom], ['created_at', '<=', $dateUntil]]);
        }

        return $query->sum('paid_fee');
    }

    public function getCashFeesAttribute($dateFrom = null, $dateUntil = null)
    {
        return $this->getFees($dateFrom, $dateUntil, 'нал');
    }

    public function getCashlessFeesAttribute($dateFrom = null, $dateUntil = null)
    {
        return $this->getFees($dateFrom, $dateUntil, 'безнал');
    }

    public function getCardFeesAttribute($dateFrom = null, $dateUntil = null)
    {
        return $this->getFees($dateFrom, $dateUntil, 'карта');
    }

    /**
     * consumptions for all matches
     * @return int
     */
    public function getExpenses($method, $dateFrom, $dateUntil, $gyms, $common)
    {
        $c = new Consumption();
        $total = 0;
        $commonCons = 0;
        $dayExpenses = DayExpense::getDayExpensesInGym($gyms, $dateFrom, $dateUntil);
        if ($common) {
            if ($method == 'нал') {
                $commonCons = $c->getAmountCashAttribute($dateFrom, $dateUntil);
            } elseif ($method == 'безнал') {
                $commonCons = $c->getAmountCashlessAttribute($dateFrom, $dateUntil);
            } else {
                $commonCons = $c->getAmountCardAttribute($dateFrom, $dateUntil);
            }
        }

        foreach ($dayExpenses as $dExp) {
            if ($dExp->hasPhoto && $dExp->hasPhoto->name == $method) {
                $total += $dExp->photo_cost;
            }
            if ($dExp->hasVideo && $dExp->hasVideo->name == $method) {
                $total += $dExp->video_cost;
            }
            if ($dExp->hasDoctor && $dExp->hasDoctor->name == $method) {
                $total += $dExp->doctor_cost;
            }
            if ($dExp->videoEdit && $dExp->videoEdit->name == $method) {
                $total += $dExp->edit_cost;
            }
            if ($dExp->otherMethod && $dExp->otherMethod->name == $method) {
                $total += $dExp->other;
            }
            if ($dExp->hasRent->name == $method) {
                $total += $dExp->rent_cost;
            }
            if ($dExp->hasCurator->name == $method) {
                $total += $dExp->curator_cost;
            }
        }


        return $total + $commonCons;
    }

    public function getRefereeExpenses($dateFrom = null, $dateUntil = null, $gyms = null)
    {
        $total = 0;
        $matches = self::with('league');
        if ($dateFrom && $dateUntil) {
            $matches = $matches->where([['match_date', '>=', $dateFrom], ['match_date', '<=', $dateUntil]]);
        }
        if ($gyms) {
            $matches = $matches->whereIn('gym_id', $gyms);
        }
        $allMatches = $matches->get();
        foreach ($allMatches as $match) {
            $total += $match->league->referee_cost;
        }
        return $total;
    }

    public function getCashlessExpensesAttribute($dateFrom = null, $dateUntil = null, $gyms = null, $common = false)
    {

        return $this->getExpenses('безнал', $dateFrom, $dateUntil, $gyms, $common);
    }

    public function getCashExpensesAttribute($dateFrom = null, $dateUntil = null, $gyms = null, $common = false)
    {


        return $this->getExpenses('нал', $dateFrom, $dateUntil, $gyms, $common) + $this->getRefereeExpenses($dateFrom, $dateUntil, $gyms);
    }

    public function getCardExpensesAttribute($dateFrom = null, $dateUntil = null, $gyms = null, $common = false)
    {


        return $this->getExpenses('карта', $dateFrom, $dateUntil, $gyms, $common);
    }

    public function getTotalExpensesAttribute()
    {
        return $this->cashless_expenses + $this->cash_expenses + $this->card_expenses;
    }

    /*
     * total amount of money
     */

    public function getTotalAttribute()
    {
        return $this->total_incomes - $this->total_expenses;
    }

    public function getTeamDebt(int $teamId)
    {
        return $this->debts()->whereTeamId($teamId)->get();
    }

    public function getTotalDebtAttribute($dateFrom = null, $dateUntil = null, $gyms = null)
    {
        $query = Debt::allDebts()->whereIsRepaid(0);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['debt_day', '>=', $dateFrom], ['debt_day', '<=', $dateUntil]]);
        }
        if ($gyms) {
            $query = $query->whereIn('gym_id', $gyms);
        }
        return $query->sum('debt_amount');
    }

}
