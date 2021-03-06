<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Consumption extends Model
{

    protected $fillable = ['consumption_sum', 'comment', 'created_at'];
    public $timestamps = false;

    public function consumptionType()
    {
        return $this->belongsTo(ConsumptionType::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }

    public function setCreatedAtAttribute(string $value)
    {
        $this->attributes['created_at'] = !empty($value) ? Carbon::createFromFormat('d-m-Y', $value) : null;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    /**
     * Get total sum depending on the payment method
     * @param type $dateFrom
     * @param type $dateUntil
     * @return type integer
     */
    public function getAmount(Carbon $dateFrom = null, Carbon $dateUntil = null, string $method)
    {
        $query = Consumption::join('payment_methods', 'consumptions.method_id', '=', 'payment_methods.id')
            ->where('payment_methods.name', '=', $method);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['created_at', '>=', $dateFrom], ['created_at', '<=', $dateUntil]]);
        }
        return $query->sum('consumption_sum');
    }

    public function getAmountCashlessAttribute(Carbon $dateFrom = null, Carbon $dateUntil = null)
    {
        return $this->getAmount($dateFrom, $dateUntil, 'безнал');
    }

    public function getAmountCashAttribute($dateFrom = null, $dateUntil = null)
    {
        return $this->getAmount($dateFrom, $dateUntil, 'нал');
    }

    public function getAmountCardAttribute(Carbon $dateFrom = null, Carbon $dateUntil = null)
    {
        return $this->getAmount($dateFrom, $dateUntil, 'карта');
    }

    public function getTotalAmountAttribute()
    {
        return $this->amount_cash + $this->amount_cashless + $this->amount_card;
    }

}
