<?php

namespace App\Http\Controllers;

use App\Models\Match;
use App\Models\PaymentMethod;
use App\Models\Gym;
use App\Models\League;
use App\Models\Team;
use App\Models\Referee;
use App\Models\Debt;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MatchController extends Controller
{

    /**
     * Display a listing of the resource.
     * Display list of matches associated
     * with current league
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('manager', ['except' => ['showReport', 'filterReport']]);
        //$this->middleware('admin');
    }

    public function index(Gym $gym)
    {
        //$gym = $gym->with(['matches','dayExpenses']);
        $matches = $gym->getTodayMatches();
        $dExp = $gym->getTodayExpenses()->first();
        if (\request("main_date")) {
            $date = new Carbon(request('main_date'));
            $matches = $gym->getMatchesOnDate($date);
            $dExp = $gym->getExpensesOnDate($date)->first();
            $selectedDate = $date->format('d-m-Y');
            $incomes = $gym->getDayIncomesAttribute($date);
            $expenses = $gym->getDayExpenseAttribute($date);
            $debt = $gym->getDayDebtAttribute($date);
            $total = $incomes - $expenses;

            return view('matches.index', compact('matches', 'dExp', 'gym', 'selectedDate', 'incomes', 'expenses', 'total', 'debt'));
        }

        return view('matches.index', compact('matches', 'gym', 'dExp'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Gym $gym)
    {

        $allMethods = PaymentMethod::all();
        $methods = $allMethods->take(3)->all();
        $referees = Referee::all();
        $leagues = League::with('teams')->get();
        $rent = $gym->rent;
        return compact('allMethods', 'methods', 'referees', 'leagues', 'rent');
    }

    public function store(Request $request, Gym $gym, Match $match)
    {

        $date = new Carbon($request->input('match_date'));
        $match->fill($request->all());
        $homeFee = $request->input('home_fee');
        $guestFee = $request->input('guest_fee');

        $rent = $gym->rent;

        $league = $request->input('league_list');

        $homeTeam = $request->input('home_list');
        $guestTeam = $request->input('guest_list');
        $referee = $request->input('referee_list');
        //payment methods associate
        $homeMethod = $request->input('home_method_list');

        $guestMethod = $request->input('guest_method_list');
        $match->homeTeam()->associate($homeTeam);
        $match->guestTeam()->associate($guestTeam);
        $match->league()->associate($league);
        $match->referee()->associate($referee);
        $match->gym()->associate($gym);
        $match->homeMethod()->associate($homeMethod);
        $match->guestMethod()->associate($guestMethod);
        $match->save();
        if ($homeFee < $rent) {
            $this->saveDebt($date, $rent - $homeFee, $homeTeam, $match->id);
        }
        if ($guestFee < $rent) {
            $this->saveDebt($date, $rent - $guestFee, $guestTeam, $match->id);
        }
        if ($homeMethod == 4) {
            $home = Team::findOrFail($homeTeam);
            $balance = $home->balance - $request->input('home_fee');
            $home->update(['balance' => $balance]);
        }
        if ($guestMethod == 4) {
            $guest = Team::findOrFail($guestTeam);
            $balance = $guest->balance - $request->input('guest_fee');
            $guest->update(['balance' => $balance]);
        }
        $response = $gym->getDayMatchesBalance($date);
        return response()->json(compact('response', 'match'));
        //return $match;
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Match $match
     * @return \Illuminate\Http\Response
     */
    public function edit(Match $match)
    {
        $allMethods = PaymentMethod::all();
        $methods = $allMethods->take(3)->all();
        $referees = Referee::all();
        $leagues = League::with('teams')->get();
        $league = $match->league;
        $homeTeams = $league->teams;
        $guestTeams = $match->editMatchTeams($league->id);
        return compact('methods', 'allMethods', 'referees', 'homeTeams', 'guestTeams', 'match', 'leagues');
    }

    public function update(Request $request, Gym $gym, Match $match)
    {
        $date = new Carbon($request->input('match_date'));
        $homeTeam = $request->input('home_list');
        $guestTeam = $request->input('guest_list');
        $referee = $request->input('referee_list');
        $league = $request->input('league_list');

        /* $currHomeTeam = $match->home_id;
          $currGuestTeam = $match->guest_id; */
        $homeFee = $request->input('home_fee');
        $guestFee = $request->input('guest_fee');
        $rent = $gym->rent;
        //payment methods associate
        $homeMethod = $request->input('home_method_list');
        $guestMethod = $request->input('guest_method_list');
        /*
       * Когда меняем команду при редактировании
       *
       */
        if (!strcmp($homeTeam, $match->home_id)) {
            if ($homeFee < $rent) {
                $this->saveDebt($date, $rent - $homeFee, $homeTeam, $match->id);
                $debt = $this->findDebt($match->home_id, $match)->first();
                if ($debt) {
                    $debt->delete();
                }
            }
        }
        if (!strcmp($guestTeam, $match->guest_id)) {
            if ($guestFee < $rent) {
                $this->saveDebt($date, $rent - $guestFee, $guestTeam, $match->id);
                $debt = $this->findDebt($match->guest_id, $match)->first();
                if ($debt) {
                    $debt->delete();
                }
            }
        }
        /*
       * Когда меняем сумму аренды
       *
       */
        if ($homeFee != $match->home_fee) {
            $debt = $this->findDebt($match->home_id, $match)->first();
            if ($debt && $homeFee < $rent) {
                $debt->update(['debt_amount' => $rent - $homeFee]);
            } elseif ($debt && $homeFee >= $rent) {
                $debt->delete();
            } elseif ($debt === null && ($homeFee < $rent)) {
                $this->saveDebt($date, $rent - $homeFee, $homeTeam, $match->id);
            }
        }
        if ($guestFee != $match->guest_fee) {
            $debt = $this->findDebt($match->guest_id, $match)->first();
            if ($debt) {
                if ($guestFee < $rent) {
                    $debt->update(['debt_amount' => $rent - $guestFee]);
                } elseif ($guestFee >= $rent) {
                    $debt->delete();
                }
            } elseif ($debt === null && ($homeFee < $rent)) {
                $this->saveDebt($date, $rent - $homeFee, $homeTeam, $match->id);
            }
        }

        $match->homeTeam()->associate($homeTeam);
        $match->guestTeam()->associate($guestTeam);
        $match->league()->associate($league);
        $match->referee()->associate($referee);
        $match->gym()->associate($gym);

        $match->homeMethod()->associate($homeMethod);
        $match->guestMethod()->associate($guestMethod);
        $currHomeFee = $match->home_fee;
        $newHomeFee = $request->input('home_fee');
        $currGuestFee = $match->guest_fee;
        $newGuestFee = $request->input('guest_fee');

        if ($homeMethod == 4 && $currHomeFee != $newHomeFee) {
            $home = Team::findOrFail($homeTeam);
            $balance = $home->balance - $newHomeFee + $currHomeFee;
            $home->update(['balance' => $balance]);
        }
        if ($guestMethod == 4 && $currGuestFee != $newGuestFee) {
            $guest = Team::findOrFail($guestTeam);
            $balance = $guest->balance - $newGuestFee + $currGuestFee;
            $guest->update(['balance' => $balance]);
        }
        if ($homeMethod == 4 && $match->home_method_id != 4) {
            $home = Team::findOrFail($homeTeam);
            $balance = $home->balance - $newHomeFee;
            $home->update(['balance' => $balance]);
        }
        if ($guestMethod == 4 && $match->guest_method_id != 4) {
            $guest = Team::findOrFail($guestTeam);
            $balance = $guest->balance - $newGuestFee;
            $guest->update(['balance' => $balance]);
        }
        if ($homeMethod != 4 && $match->home_method_id == 4) {
            $home = Team::findOrFail($homeTeam);
            $balance = $home->balance + $currHomeFee;
            $home->update(['balance' => $balance]);
        }
        if ($guestMethod != 4 && $match->guest_method_id == 4) {
            $guest = Team::findOrFail($guestTeam);
            $balance = $guest->balance + $currGuestFee;
            $guest->update(['balance' => $balance]);
        }
        $match->update($request->all());
        $response = $gym->getDayMatchesBalance($date);
        return response()->json(compact('response', 'match'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Match $match
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gym $gym, Match $match)
    {
        $date = $match->match_date;
        $homeFee = $match->home_fee;
        $guestFee = $match->guest_fee;
        if ($match->home_method_id == 4) {
            $home = $match->homeTeam;
            $balance = $home->balance + $homeFee;
            $home->update(['balance' => $balance]);
        }
        if ($match->guest_method_id == 4) {
            $guest = $match->guestTeam;
            $balance = $guest->balance + $guestFee;
            $guest->update(['balance' => $balance]);
        }
        $match->debts()->delete();
        $match->delete();
        $response = $gym->getDayMatchesBalance($date);
        return $response;
    }

    public function ajaxSelect()
    {
        $leagueId = request('league_id');
        $homeId = request('home_id');
        return Match::getAvailableTeams($leagueId, $homeId);
    }

    public function ajaxHomeSelect()
    {
        $leagueId = request('league_id');
        return League::findOrFail($leagueId)
            ->teams()
            ->orderBy('name', 'asc')
            ->get();
    }

    //времменно
//    public function ajaxEditSelect(League $league)
//    {
//      $homeId = request('home_id');
//      $guestId = request('guest_id');
//      return Match::getAvailableEditTeams($league->id, $homeId, $guestId);
//    }
    public function showReport(Match $match)
    {
        /* $match = $matchModel->with(['hasPhoto',
          'hasVideo',
          'videoEdit',
          'hasVideo',
          'otherMethod',
          'refereeMethod',])->first(); */
        //$match = $matchModel;
        $gyms = Gym::all();
        return view('matches.show', compact('match', 'gyms'));
    }

    public function filterReport(Match $match)
    {
        $dateFrom = request('date_from') ? new Carbon(request('date_from')) : null;
        $dateUntil = request('date_until') ? new Carbon(request('date_until')) : null;
        $gyms = request('gym_list');
        $fees = filter_var(request('fees'), FILTER_VALIDATE_BOOLEAN);
        $common = filter_var(request('common'), FILTER_VALIDATE_BOOLEAN);
        $response = [];
        $response['cash_expenses'] = $match->getCashExpensesAttribute($dateFrom, $dateUntil, $gyms, $common);
        $response['cashless_expenses'] = $match->getCashlessExpensesAttribute($dateFrom, $dateUntil, $gyms, $common);
        $response['card_expenses'] = $match->getCardExpensesAttribute($dateFrom, $dateUntil, $gyms, $common);
        $response['total_expenses'] = $response['cash_expenses'] + $response['cashless_expenses'] + $response['card_expenses'];
        $response['cash_incomes'] = $match->getCashIncomesAttribute($dateFrom, $dateUntil, $gyms, $fees, $common);
        $response['cashless_incomes'] = $match->getCashlessIncomesAttribute($dateFrom, $dateUntil, $gyms, $fees, $common);
        $response['card_incomes'] = $match->getCardIncomesAttribute($dateFrom, $dateUntil, $gyms, $fees, $common);
        $response['total_incomes'] = $response['cash_incomes'] + $response['cashless_incomes'] + $response['card_incomes'];
        $response['total_debt'] = $match->getTotalDebtAttribute($dateFrom, $dateUntil, $gyms);
        $response['total'] = $response['total_incomes'] - $response['total_expenses'];
        return response()->json(compact('response'));
    }

    public function checkBalance(Team $team)
    {
        return response()->json(['balance' => $team->balance, 'name' => $team->name]);
    }

    public function saveDebt($date, $amount, $teamId, $matchId)
    {
        $debt = new Debt(['debt_amount' => $amount, 'debt_day' => $date]);
        $debt->team()->associate($teamId);
        $debt->match()->associate($matchId);
        $debt->save();
    }

    public function findDebt($teamId, $match)
    {
        return $match->getTeamDebt($teamId);
    }

}
