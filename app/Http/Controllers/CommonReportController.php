<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Consumption;
use Carbon\Carbon;

class CommonReportController extends Controller
{
    private $cons;
    private $income;

    public function __construct(Consumption $cons, Income $income)
    {
        $this->middleware('org');
        $this->cons = $cons;
        $this->income = $income;
    }

    public function showReport()
    {
        return view('common.index', ['income' => $this->income, 'cons' => $this->cons]);
    }

    public function filterReport()
    {
        $dateFrom = request('date_from') ? new Carbon(request('date_from')) : null;
        $dateUntil = request('date_until') ? new Carbon(request('date_until')) : null;
        $consResponse = [];
        $incomeResponse = [];
        $consResponse['cash'] = $this->cons->getAmountCashAttribute($dateFrom, $dateUntil);
        $consResponse['cashless'] = $this->cons->getAmountCashlessAttribute($dateFrom, $dateUntil);
        $consResponse['card'] = $this->cons->getAmountCardAttribute($dateFrom, $dateUntil);
        $consResponse['total'] = $consResponse['cash'] + $consResponse['cashless'] + $consResponse['card'];
        $incomeResponse['cash'] = $this->income->getAmountCashAttribute($dateFrom, $dateUntil);
        $incomeResponse['cashless'] = $this->income->getAmountCashlessAttribute($dateFrom, $dateUntil);
        $incomeResponse['card'] = $this->income->getAmountCardAttribute($dateFrom, $dateUntil);
        $incomeResponse['total'] = $incomeResponse['cash'] + $incomeResponse['cashless'] + $incomeResponse['card'];
        $incomeResponse['difference'] = $incomeResponse['total'] - $consResponse['total'];
        return response()->json(compact('consResponse', 'incomeResponse'));
    }
}
