<?php

namespace App\Http\Controllers;

use App\Models\Consumption;
use App\Models\ConsumptionType;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ConsumptionController extends Controller {

    public function __construct() {
        $this->middleware('org');
    }

    public function index() {
        $consumptions = Consumption::all();
        return view('consumptions.index', compact('consumptions'));
    }

    public function show() {
        
    }

    public function create() {
        $types = ConsumptionType::all();
        $methods = PaymentMethod::take(3)->get();
        return response()->json(compact('types', 'methods'));
    }

    public function store(Request $request, Consumption $consumption) {
        $consumption->fill($request->all());
        $type = $request->get('name');
        $method = $request->get('payment_method');
        $consumption->consumptionType()->associate($type);
        $consumption->paymentMethod()->associate($method);
        $consumption->save();
        return $consumption;
    }

    public function edit(Consumption $consumption) {
        $types = ConsumptionType::all();
        $methods = PaymentMethod::all();
        return response()->json(compact('types', 'methods', 'consumption'));
    }

    public function update(Request $request, Consumption $consumption) {
        $consumption->fill($request->all());
        $type = $request->get('name');
        $method = $request->get('payment_method');
        $consumption->consumptionType()->associate($type);
        $consumption->paymentMethod()->associate($method);
        $consumption->update($request->all());
    }

    public function destroy(Consumption $consumption) {
        $consumption->delete();
    }

}
