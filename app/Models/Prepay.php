<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Prepay extends Model
{
    protected $fillable = ['payday','amount'];
    public $timestamps = false;
    public function setPayDayAttribute($value) {
        $this->attributes['payday'] = !empty($value) ? \Carbon\Carbon::createFromFormat('d-m-Y', $value) : null;
    }
    public function getPayDayAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
