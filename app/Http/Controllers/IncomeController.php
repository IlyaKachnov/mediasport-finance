<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeType;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class IncomeController extends Controller {

    public function __construct() {
       $this->middleware('org');
    }

    public function index() {
        $incomes = Income::all();
        return view('incomes.index', compact('incomes'));
    }

    public function store(Request $request, Income $income) {
        $income->fill($request->all());
        $type = $request->get('name');
        $method = $request->get('payment_method');
        $income->incomeType()->associate($type);
        $income->paymentMethod()->associate($method);
        $income->save();
        return $income;
    }

    public function update(Request $request, Income $income) {
        $income->fill($request->all());
        $type = $request->get('name');
        $method = $request->get('payment_method');
        $income->incomeType()->associate($type);
        $income->paymentMethod()->associate($method);
        $income->update($request->all());
    }

    public function destroy(Income $income) {
        $income->delete();
    }

    public function create() {
        $types = IncomeType::all();
        $methods = PaymentMethod::take(3)->get();
        return response()->json(compact('types', 'methods'));
    }

    public function edit(Income $income) {
        $types = IncomeType::all();
        $methods = PaymentMethod::all();
        return response()->json(compact('types', 'methods', 'income'));
    }

}
