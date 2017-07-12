<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Prepay;

class PrepayController extends Controller {

    public function index(Team $team) {
        return view('prepays.index', compact('team'));
    }

    public function store(Request $request, Team $team, Prepay $prepay) {

        $prepay->fill($request->all());
        $prepay->team()->associate($team);
        $prepay->save();
        $balance = $request->input('amount') + $team->balance;
        $team->update(['balance' => $balance]);
        return $prepay;
    }

    public function update(Request $request, Team $team, Prepay $prepay) {
        $prepay->update($request->all());
        $balance = $request->input('amount') + $team->balance;
        $team->update(['balance' => $balance]);
    }

    public function destroy(Team $team, Prepay $prepay) {
        $balance = $team->balance - $prepay->amount;
        $team->update(['balance' => $balance]);
        $prepay->delete();
    }

}
