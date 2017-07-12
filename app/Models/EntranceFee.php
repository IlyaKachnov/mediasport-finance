<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Team;
class EntranceFee extends Model
{
    protected $fillable = ['paid_fee'];
    public $timestamps = false;
    public function team()
    {
        
        return $this->belongsTo(Team::class);
    }
    public function method()
    {
        return $this->belongsTo(PaymentMethod::class,'method_id');
    }
  
        public function scopeFee($query)
    {
        return $query->join('payment_methods','entrance_fees.method_id','=','payment_methods.id')
                ->join('teams','teams.id','=','entrance_fees.team_id');
              
    }
    public function getFeesForLeague($leagueId)
    {
        $query = EntranceFee::select('entrance_fees.id', 'entrance_fees.paid_fee','entrance_fees.team_id','entrance_fees.method_id')
                ->join('teams','team_id','=','teams.id')
                ->where('teams.league_id','=',$leagueId)
                ->get();
        return $query;
    }
    public function getFeePercentAttribute()
    {
        $team = $this->team;
        $total = $team->league->total_fees;
        return (int)(($this->paid_fee * 100)/$total); 
    }
  
    public static function getAvailableTeams($leagueId)
    {
        return Team::available($leagueId) ->get();
   
    }
    public function getEditTeams($leagueId)
    {
      $id = $this->team->id;
      return Team::available($leagueId)->orWhere('id','=',$id)->get();
    }
}
