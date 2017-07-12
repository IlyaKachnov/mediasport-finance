<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model {

    protected $fillable = ['income_sum', 'comment','created_at'];
    public $timestamps = false;

    public function incomeType() {
        return $this->belongsTo(IncomeType::class);
    }

    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }

    public function scopeTotal($query) {
        return $query->join('payment_methods', 'incomes.method_id', '=', 'payment_methods.id');
    }

    public function setCreatedAtAttribute($value) {
        $this->attributes['created_at'] = !empty($value) ? \Carbon\Carbon::createFromFormat('d-m-Y', $value) : null;
    }

    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }

    /**
     * Get total sum depending on the payment method
     * @param type $dateFrom
     * @param type $dateUntil
     * @return type integer
     */
    public function getAmount($dateFrom, $dateUntil, $method) {
        $query = Income::total()->where('payment_methods.name', '=', $method);
        if ($dateFrom && $dateUntil) {
            $query = $query->where([['created_at', '>=', $dateFrom], ['created_at', '<=', $dateUntil]]);
        }
        return $query->sum('income_sum');
    }

    public function getAmountCashlessAttribute($dateFrom = null, $dateUntil = null) {
        return $this->getAmount($dateFrom, $dateUntil, 'безнал');
    }

    public function getAmountCashAttribute($dateFrom = null, $dateUntil = null) {
        return $this->getAmount($dateFrom, $dateUntil, 'нал');
    }

    public function getAmountCardAttribute($dateFrom = null, $dateUntil = null) {
        return $this->getAmount($dateFrom, $dateUntil, 'карта');
    }

    public function getTotalAmountAttribute() {
        return $this->amount_cash + $this->amount_cashless + $this->amount_card;
    }

}
