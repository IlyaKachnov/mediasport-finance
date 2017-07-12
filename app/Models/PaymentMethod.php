<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
    public function homeMatches()
    {
        return $this->hasMany(Match::class,'home_method_id');
    }
      public function guestMatches()
    {
        return $this->hasMany(Match::class,'guest_method_id');
    }
}
