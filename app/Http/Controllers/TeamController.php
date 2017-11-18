<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\League;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeamController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('org');
    }

    public function index(League $league)
    {
        return view('teams.index', compact('league'));
    }

    public function showReport()
    {
        $teams = Team::with(['matchesAsHome',
            'matchesAsGuest',
            'matchesAsHome.hasPhoto',
            'matchesAsHome.hasVideo',
            'matchesAsHome.videoEdit',
            'matchesAsHome.hasDoctor',
            'matchesAsHome.otherMethod',
            'matchesAsHome.refereeMethod',
            'matchesAsHome.league'
        ])->get();
        //$teams = Team::all();
        $leagues = League::all();
        return view('teams.show', compact('teams', 'leagues'));
    }

    public function filterReport()
    {
        $response = [];
        $dateFrom = request('date_from') ? new Carbon(request('date_from')) : null;
        $dateUntil = request('date_until') ? new Carbon(request('date_until')) : null;
        $league = request('league_list');
        $fees = filter_var(request('fees'), FILTER_VALIDATE_BOOLEAN);
        $teams = Team::getTeamsInLeague($league);
        foreach ($teams as $team) {
            $response[$team->id]['name'] = $team->name;
            $response[$team->id]['cash_expenses'] = $team->getCashExpensesAttribute($dateFrom, $dateUntil);
            $response[$team->id]['cashless_expenses'] = $team->getCashlessExpensesAttribute($dateFrom, $dateUntil);
            $response[$team->id]['card_expenses'] = $team->getCardExpensesAttribute($dateFrom, $dateUntil);
            $response[$team->id]['total_expenses'] = $response[$team->id]['cash_expenses'] + $response[$team->id]['cashless_expenses'] + $response[$team->id]['card_expenses'];
            $response[$team->id]['cash_incomes'] = $team->getCashIncomesAttribute($dateFrom, $dateUntil, $fees);
            $response[$team->id]['cashless_incomes'] = $team->getCashlessIncomesAttribute($dateFrom, $dateUntil, $fees);
            $response[$team->id]['card_incomes'] = $team->getCardIncomesAttribute($dateFrom, $dateUntil, $fees);
            $response[$team->id]['total_incomes'] = $response[$team->id]['cash_incomes'] + $response[$team->id]['cashless_incomes'] + $response[$team->id]['card_incomes'];
            $response[$team->id]['total'] = $response[$team->id]['total_incomes'] - $response[$team->id]['total_expenses'];
        }

        return response()->json(compact('response'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, League $league, Team $team)
    {
        $team->fill($request->all());
        $team->league()->associate($league);
        $team->save();
        return $team;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Team $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league, Team $team)
    {
//        $team->league()->associate($league);
        $team->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Team $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(League $league, Team $team)
    {
        $team->prepays()->delete();
        $team->debts()->delete();
        $team->delete();
    }

    public function checkName()
    {

        $id = \request('id');
        $name = \request('name');
        //on edit team
        if ($id > 0) {
            $team = Team::findOrFail($id);
            if ($team->name == $name) {
                return response()->json(['response' => true]);
            }

            $team = Team::whereName($name)->first();
            if ($team) {
                return response()->json(['response' => false]);
            }

            return response()->json(['response' => true]);
        }
        //on create team
        $team = Team::whereName($name)->first();
        if ($team) {
            return response()->json(['response' => false]);
        }

        return response()->json(['response' => true]);
    }
}
