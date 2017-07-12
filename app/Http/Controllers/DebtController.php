<?php

namespace App\Http\Controllers;
use App\Models\Team;
use App\Models\Debt;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index(Team $team)
    {
        return view('debts.index',  compact('team'));
    }
    public function changeStatus(Debt $debt)
    {
        $status = request('status');
        $newStatus = $status ? 0 : 1;
        $debt->update(['is_repaid'=>$newStatus]);
        return response()->json(['status'=>$newStatus]);
    }
}
