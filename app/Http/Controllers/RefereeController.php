<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Referee;

class RefereeController extends Controller {

    public function __construct() {
         $this->middleware('org');
    }

    public function index() {
        $referees = Referee::all();
        return view('referees.index', compact('referees'));
    }

    public function showReport() {
        $referees = Referee::with('matches')->get();
        return view('referees.show', compact('referees'));
    }

    public function filterReport() {
        $response = [];
        $dateFrom = request('date_from') ? new Carbon(request('date_from')) : null;
        $dateUntil = request('date_until') ? new Carbon(request('date_until')) : null;
        $referees = Referee::all();
        foreach ($referees as $referee) {
            $response[$referee->id]['number'] = $referee->getNumberAttribute($dateFrom, $dateUntil);
            $response[$referee->id]['amount'] = $referee->getTotalAmountAttribute($dateFrom, $dateUntil);
        }
        return response()->json(['response' => $response]);
    }

    public function create() {
        return view('referees.create');
    }

    public function store(Request $request) {
        Referee::create($request->all());
        return redirect('referees');
    }

    public function edit(Referee $referee) {

        return view('referees.edit', compact('referee'));
    }

    public function update(Referee $referee, Request $request) {
        $referee->update($request->all());
        return redirect('referees');
    }

    public function destroy(Referee $referee) {
        $referee->matches()->dissociate();       
        $referee->delete();
    }

}
