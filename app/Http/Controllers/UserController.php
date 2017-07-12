<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\League;
use App\Models\Gym;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::pluck('caption', 'id');
        $gyms = Gym::pluck('name', 'id');
        return view('users.create', compact('roles', 'gyms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request, User $user) {
        $role = Role::findOrFail($request->input('roles_list'));
        $user->fill($request->all());
        $password = $request->input('password');
        $gyms = $request->input('gym_list');
        $user->role()->associate($role);
        $user->save();
        $user->gyms()->attach($gyms);
        Mail::to($user)->send(new SendMail($user,$password));
        return redirect('users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        $roles = Role::pluck('caption', 'id');
        $gyms = Gym::pluck('name', 'id');
        return view('users.edit', compact('user', 'roles', 'gyms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user) {
        $role = request('roles_list');
        $gyms = (array)request('gym_list');
        $user->role()->associate($role);
        $user->update();
        $user->gyms()->sync($gyms);
        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        $user->gyms()->detach();
        $user->delete();
    }

}
