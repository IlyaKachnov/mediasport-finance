<?php

namespace App\Http\Controllers;
use App\Models\Income;
use App\Models\Consumption;
use Carbon\Carbon;
class CommonReportController extends Controller
{
    public function __construct() {
        $this->middleware('org');
    }
    public function showReport(Consumption $cons, Income $income)
    {
        return view('common.index',  compact('income','cons'));
    }
   public function filterReport(Consumption $cons, Income $income)
   {
    $dateFrom = request('date_from') ? new Carbon(request('date_from')) :null;
    $dateUntil = request('date_until') ? new Carbon(request('date_until')) :null; 
    $consResponse = [];
    $incomeResponse = [];
    $consResponse['cash'] = $cons->getAmountCashAttribute($dateFrom, $dateUntil);
    $consResponse['cashless'] = $cons->getAmountCashlessAttribute($dateFrom, $dateUntil);
    $consResponse['card'] = $cons->getAmountCardAttribute($dateFrom, $dateUntil);
    $consResponse['total'] = $consResponse['cash'] +  $consResponse['cashless'] + $consResponse['card'];
    $incomeResponse['cash'] = $income->getAmountCashAttribute($dateFrom, $dateUntil);
    $incomeResponse['cashless'] = $income->getAmountCashlessAttribute($dateFrom, $dateUntil);
    $incomeResponse['card'] = $income->getAmountCardAttribute($dateFrom, $dateUntil);
    $incomeResponse['total'] = $incomeResponse['cash'] +  $incomeResponse['cashless'] + $incomeResponse['card'];
    $incomeResponse['difference']= $incomeResponse['total'] - $consResponse['total'];
    return response()->json(compact('consResponse','incomeResponse'));
   }
}
