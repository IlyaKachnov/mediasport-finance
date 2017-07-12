<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym;
use Carbon\Carbon;
use App\Http\Requests\GymRequest;
class GymController extends Controller {

    public function __construct() {
        $this->middleware('org');
    }

    public function index() {
        $gyms = Gym::all();
        return view('gyms.index', compact('gyms'));
    }

    public function showReport(Gym $totalGym) {
        //$gyms = Gym::all();
        $gyms = Gym::with(['matches','dayExpenses'])->get();
        return view('gyms.show', compact('gyms','totalGym'));
    }

    public function filterReport() {
        $response = [];
        $total = ['number'=>0,'total_rent'=>0, 'amount_fees'=>0,'total_expenses'=>0,'difference'=>0];
        $gyms = Gym::all();
        $dateFrom = request('date_from') ? new Carbon(request('date_from')) :null;
        $dateUntil = request('date_until') ? new Carbon(request('date_until')) :null;
        foreach ($gyms as $gym) {

            $response[$gym->id]['number'] = $gym->getNumberAttribute($dateFrom, $dateUntil);
            $response[$gym->id]['total_rent'] = $gym->getTotalRentAttribute($dateFrom, $dateUntil);
            $response[$gym->id]['amount_fees'] = $gym->getAmountFeesAttribute($dateFrom, $dateUntil);
            $response[$gym->id]['total_expenses'] = $gym->getTotalExpensesAttribute($dateFrom, $dateUntil);
            $response[$gym->id]['difference'] = $response[$gym->id]['amount_fees'] - $response[$gym->id]['total_expenses'] - $response[$gym->id]['total_rent'];
            $total['number']+=$response[$gym->id]['number'];
            $total['total_rent']+= $response[$gym->id]['total_rent'];
            $total['amount_fees']+=$response[$gym->id]['amount_fees'];
            $total['total_expenses']+= $response[$gym->id]['total_expenses'];
            $total['difference'] += $response[$gym->id]['difference'];
        }
        return response()->json(compact('response','total'));
    }

    public function create() {
        return view('gyms.create');
    }

    public function store(GymRequest $request) {
        Gym::create($request->all());
        return redirect('gyms');
    }

    public function edit(Gym $gym) {

        return view('gyms.edit', compact('gym'));
    }

    public function update(Gym $gym, GymRequest $request) {
        $gym->update($request->all());
        return redirect('gyms');
    }

    public function destroy(Gym $gym) {
        $gym->leagues()->detach();
        $gym->matches()->dissociate();
        $gym->delete();
    }

}
