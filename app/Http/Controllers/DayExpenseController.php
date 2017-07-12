<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\DayExpense;
use App\Models\Gym;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DayExpenseController extends Controller {

    public function create() {
        $methods = PaymentMethod::take(3)->get();
        return compact('methods');
    }

    public function store(Request $request, Gym $gym, DayExpense $dExp) {
        $date = new Carbon($request->input('day'));
        $dExp->fill($request->all());
        $photo = $request->input('has_photo_list');
        $video = $request->input('has_video_list');
        $doctor = $request->input('has_doctor_list');
        $videoEdit = $request->input('video_edit_list');
        $other = $request->input('other_method_list');
        $curator = $request->input('curator_list');
        $rent = $request->input('rent_list');
        $dExp->hasPhoto()->associate($photo);
        $dExp->hasVideo()->associate($video);
        $dExp->hasDoctor()->associate($doctor);
        $dExp->videoEdit()->associate($videoEdit);
        $dExp->otherMethod()->associate($other);
        $dExp->hasCurator()->associate($curator);
        $dExp->hasRent()->associate($rent);
        $dExp->gym()->associate($gym);
        $dExp->save();
        $response = $gym->getDayMatchesBalance($date);
        return response()->json(compact('response', 'dExp'));
    }

    public function edit(DayExpense $dExp) {
        $methods = PaymentMethod::take(3)->get();
        return compact('methods', 'dExp');
    }

    public function update(Request $request, DayExpense $dExp) {
        $date = new Carbon($request->input('day'));
        $photo = $request->input('has_photo_list');
        $video = $request->input('has_video_list');
        $doctor = $request->input('has_doctor_list');
        $videoEdit = $request->input('video_edit_list');
        $other = $request->input('other_method_list');
        $curator = $request->input('curator_list');
        $rent = $request->input('rent_list');
        $dExp->hasPhoto()->associate($photo);
        $dExp->hasVideo()->associate($video);
        $dExp->hasDoctor()->associate($doctor);
        $dExp->videoEdit()->associate($videoEdit);
        $dExp->otherMethod()->associate($other);
        $dExp->hasCurator()->associate($curator);
        $dExp->hasRent()->associate($rent);
        $dExp->update($request->all());
        $gym = Gym::findOrFail($dExp->gym_id);
        $response = $gym->getDayMatchesBalance($date);
        return response()->json(compact('response'));
    }

    public function destroy(DayExpense $dExp) {
        $date = $dExp->day;
        $gym = Gym::findOrFail($dExp->gym_id);
        $dExp->delete();
        $response = $gym->getDayMatchesBalance($date);
        return response()->json(compact('response'));
    }

}
