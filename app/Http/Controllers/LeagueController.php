<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Http\Request;
use App\Models\Gym;

class LeagueController extends Controller
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

    public function index()
    {
        $leagues = League::all();
        return view('leagues.index', compact('leagues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, League $league)
    {
        $newLeague = $league->create($request->all());
        return $newLeague;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\League $league
     * @return \Illuminate\Http\Response
     */
    public function edit(League $league)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\League $league
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league)
    {
        $league->update($request->all());

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\League $league
     * @return \Illuminate\Http\Response
     */
    public function destroy(League $league)
    {
        $league->teams()->delete();
        $league->matches()->delete();
        $league->gyms()->detach();
        $league->delete();
    }

    public function addGyms(League $league)
    {
        return view('leagues.add-gyms', compact('league'));
    }

    public function loadGyms(League $league)
    {
        //$gyms = $gymModel->getAvailableGyms();
        $gyms = Gym::getAvailableGyms($league->id);
        return $gyms;
    }

    public function saveGyms(League $league)
    {
        $gym = request('gym');
        $rentPrice = request('rent_price');
        $league->gyms()->attach($gym, ['rent_price' => $rentPrice]);
        return response()->json(['gym' => $gym]);
    }

    public function editGyms(League $league, Gym $gym)
    {
        $gyms = Gym::getEditGyms($league->id, $gym->id);
        return response()->json(compact('gyms', 'gym', 'league'));
    }

    public function updateGyms(League $league, Gym $gym)
    {
        $rentPrice = request('rent_price');
        $newGym = request('gym');
        if ($gym != $newGym) {
            $league->gyms()->detach($gym);
            $league->gyms()->attach($newGym, ['rent_price' => $rentPrice]);
        } else {
            $league->pivot->rent_price = $rentPrice;
        }

    }

    public function deleteGyms(League $league, Gym $gym)
    {
        $league->gyms()->detach($gym);
    }

    public function checkName()
    {
        $id = \request('id');
        $name = \request('name');
        //on edit league
        if ($id > 0) {
            $league = League::findOrFail($id);
            if ($league->name == $name) {
                return response()->json(['response' => true]);
            }

            $league = League::whereName($name)->first();
            if ($league) {
                return response()->json(['response' => false]);
            }

            return response()->json(['response' => true]);
        }
        //on create league
        $league = League::whereName($name)->first();
        if ($league) {
            return response()->json(['response' => false]);
        }

        return response()->json(['response' => true]);
    }

    public function showAll()
    {
        $leagues = League::all();
        return view('leagues.all-leagues', compact('leagues'));
    }

}
