<?php

namespace App\Http\Controllers;

use App\Models\EntranceFee;
use App\Models\League;
use App\Models\PaymentMethod;
use App\Models\Team;
use Illuminate\Http\Request;

class EntranceFeeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct() {
         $this->middleware('org');
    }
    public function index(League $league, EntranceFee $fee) {

        $fees = $fee->getFeesForLeague($league->id);
        return view('fees.index', compact('fees', 'league'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(League $league) {
        $teams = EntranceFee::getAvailableTeams($league->id);
        $methods = PaymentMethod::take(3)->get();
        return compact('teams', 'methods');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, League $league, EntranceFee $fee) {
        $fee->fill($request->all());
        $fee->method()->associate($request->get('payment_method'));
        $fee->team()->associate($request->get('team'));
        $fee->save();
        $percents = $fee->getFeePercentAttribute();
        return array_merge(compact('fee'), ['fee_percent' => $percents]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EntranceFee  $entranceFee
     * @return \Illuminate\Http\Response
     */
    public function show(EntranceFee $entranceFee) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EntranceFee  $entranceFee
     * @return \Illuminate\Http\Response
     */
    public function edit(League $league, EntranceFee $fee) {
       $teams = $fee->getEditTeams($league->id);
        $methods = PaymentMethod::take(3)->get();
        return compact('teams', 'methods', 'fee');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EntranceFee  $entranceFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league, EntranceFee $fee) {

        $fee->method()->associate($request->get('payment_method'));
        $fee->team()->associate($request->get('team'));
        $fee->update($request->all());
        $percents = $fee->getFeePercentAttribute();
        return array_merge(compact('fee'), ['fee_percent' => $percents]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EntranceFee  $entranceFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(League $league, EntranceFee $fee) {
        $fee->method()->dissociate();
        $fee->team()->dissociate();
        $fee->delete();
    }

}
