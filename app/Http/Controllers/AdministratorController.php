<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
class AdministratorController extends Controller {

    use ResetsPasswords;

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('dashboard.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        $user->update($request->all());
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    /**
     * 
     * Store user avatar
     */
    public function storeAvatar(Request $request, User $user) {

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = Image::translitName($file->getClientOriginalName());
            $file->storeAs('/' . $user->id, $fileName, 'avatars');
            if ($user->image) {
                $image = $user->image;
                File::delete('avatars/' . $user->id . '/' . $image->name);
                $image->delete();
            }
            $user->image()->save(new Image(['name' => $fileName]));
        }
        return back();
    }
    public function postChange(Request $request, User $user)
    {
     
        $old = $request['old_password'];
        $password = $request['password'];
        $text = 'Пароль успешно изменен';
        $alert = 'alert-success';
        $check = $this->checkOldPassword($old, $user->password);
        if($check)
        {
             $user->forceFill([
            'password' => $password,
            'remember_token' => Str::random(60),
        ])->save();
          Mail::to($user)->send(new \App\Mail\ChangePasswordMail($user,$password));
          $this->guard()->login($user);
          
        }
        else {
            $text = 'Текущий пароль указан неверно!';
            $alert = 'alert-danger';
        }
        return response()->json(['text'=>$text,'alert'=>$alert]);
    }
    public function checkOldPassword($oldPassword, $password)
    {
        return Hash::check($oldPassword, $password);
    }


}
