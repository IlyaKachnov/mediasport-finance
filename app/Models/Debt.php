<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Debt extends Model {

    public $timestamps = false;
    protected $fillable = ['debt_day', 'debt_amount', 'is_repaid'];

    public function team() {
        return $this->belongsTo(Team::class);
    }
    public function match()
    {
      return $this->belongsTo(Match::class);
    }
        public function getDebtDayAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
       public function scopeAllDebts($query)
    {
        return $query->join('matches','matches.id','=','debts.match_id');
    }
}
