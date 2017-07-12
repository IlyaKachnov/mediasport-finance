<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayExpense extends Model {

    protected $fillable = [
        'photo_cost', 'video_cost', 'day',
        'edit_cost', 'doctor_cost', 'curator_cost',
        'rent_cost',
        'other', 'comment'
    ];
    public $timestamps = false;

    public function setDayAttribute($value) {
        $this->attributes['day'] = !empty($value) ? \Carbon\Carbon::createFromFormat('d-m-Y', $value) : null;
    }

    public function hasVideo() {
        return $this->belongsTo(PaymentMethod::class, 'has_video');
    }

    public function hasPhoto() {
        return $this->belongsTo(PaymentMethod::class, 'has_photo');
    }

    public function hasDoctor() {
        return $this->belongsTo(PaymentMethod::class, 'has_doctor');
    }

    public function videoEdit() {
        return $this->belongsTo(PaymentMethod::class, 'video_edit');
    }

    public function hasCurator() {
        return $this->belongsTo(PaymentMethod::class, 'has_curator');
    }

    public function hasRent() {
        return $this->belongsTo(PaymentMethod::class, 'has_rent');
    }

    public function otherMethod() {
        return $this->belongsTo(PaymentMethod::class, 'other_method_id');
    }

    public function gym() {
        return $this->belongsTo(Gym::class);
    }
       public function scopeWithAll($query) {
        $query->with(['hasPhoto','gym', 
           'hasVideo',
            'videoEdit',
            'hasDoctor',
            'otherMethod',
            'hasRent',
            'hasCurator',            
            ]);
    }
        public static function getDayExpensesInGym($gyms = null, $dateFrom = null, $dateUntil = null) {
        if ($gyms) {
            $dayExpenses = self::whereIn('gym_id', $gyms);
            if ($dateFrom && $dateUntil) {
                $dayExpenses->where([['day', '>=', $dateFrom], ['day', '<=', $dateUntil]]);
            }
            return $dayExpenses->get();
        } elseif ($dateFrom && $dateUntil) {
            $dayExpenses = self::where([['day', '>=', $dateFrom], ['day', '<=', $dateUntil]]);
            return $dayExpenses->withAll()->get();
        } else {
            return self::withAll()->get();
        }
    }

}
